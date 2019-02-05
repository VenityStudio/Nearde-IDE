<?php

use compress\ArchiveEntry;
use compress\Bzip2InputStream;
use compress\GzipInputStream;
use compress\Lz4InputStream;
use compress\TarArchive;
use compress\ZipArchive;
use packager\Event;
use packager\cli\Console;
use php\io\Stream;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;

function task_publish(Event $e)
{
    Tasks::runExternal('./designer', 'publish', [], ...$e->flags());
    Tasks::runExternal('./dnd-gui-tabs-ext', 'publish', [], ...$e->flags());
}

/**
 * @jppm-task prepare-ide
 * @param Event $e
 */
function task_prepareIde(Event $e)
{
    Tasks::run('publish', [], 'yes');
    Tasks::runExternal("./ide", "update");
}

/**
 * @jppm-task start-ide
 * @jppm-description Start IDE (Nearde)
 */
function task_startIde(Event $e)
{
    Tasks::runExternal('./ide', 'start', $e->args(), ...$e->flags());
}

/**
 * @jppm-task fetch-messages
 * @jppm-description Fetch all language messages from sources
 */
function task_fetchMessages($e)
{
    $buildPath = $e->package()->getConfigBuildPath();

    $regex = new Regex('(\\"|\\\')([a-z]+\\.[a-z0-9\\.]+)((\\:\\:)(.+?))?(\\\'|\\")');

    $ignoreExts = [
        'php', 'tmp', 'conf', 'ini', 'json', 'source', 'css', 'pid', 'log', 'lock', 'ws', 'gradle', 'xml',
        'axml', 'behaviour', ''
    ];
    $ignoreExts = arr::combine($ignoreExts, $ignoreExts);

    $ignores = [
        'app.hash'    => 1, 'develnext.endpoint' => 1, 'os.name' => 1, 'os.user' => 1, 'os.version' => 1, 'java.version' => 1,
        'user.home'   => 1, 'hub.develnext.org' => 1, 'develnext.org' => 1, 'develnext.path' => 1, 'splash.avatar' => 1,
        'splash.name' => 1, 'script.name' => 1, 'script.desc' => 1, 'script.author' => 1, 'user.language' => 1,
        'ide.language' => 1,
    ];

    $data = [];
    $ruData = fs::parse("./ide/misc/languages/ru/messages.ini");

    $dirs = ["./ide/src", "./ide/platforms", "./bundles"];

    foreach ($dirs as $dir) {
        fs::scan($dir, [
            'excludeDirs' => true,
            'extensions'  => ['php', 'fxml', 'conf', 'xml'],
            'callback'    => function ($filename) use ($regex, $ignoreExts, $ignores, &$data, &$ruData) {
                //echo "-> ", $filename, "\n";
                $content = fs::get($filename);

                $r = $regex->with($content); //->withFlags('');

                foreach ($r->all() as $groups) {
                    $var = $groups[2];

                    if ($ignores[$var]) continue;
                    if (str::count($var, '.') === 1 && $ignoreExts[fs::ext($var)]) continue;

                    $data[$var] = '';

                    if (!$ruData[$var]) {
                        $ruData[$var] = $groups[5] ?? $var;
                    }
                }
            }
        ]);
    }

    Tasks::createFile("$buildPath/messages.ini");
    //Tasks::createFile("$buildPath/messages.ru.ini");

    ksort($data);
    ksort($ruData);

    //fs::format("$buildPath/messages.ini", $data);
    fs::format("$buildPath/messages.ini", $ruData);
}

/**
 * @jppm-task build-ide
 */
function task_buildIde(Event $e)
{
    Tasks::runExternal("./ide", "install");

    Tasks::copy("./ide/vendor", "./ide/build/vendor/");
    Tasks::copy('./ide/misc', './ide/build/');

    Tasks::deleteFile('./launcher/build');
    Tasks::runExternal('./launcher', 'build');
    Tasks::copy('./launcher/build/NeardeLauncher.jar', './ide/build');

    Tasks::runExternal('./ide', 'copySourcesToBuild');
}
