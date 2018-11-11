<?php
namespace ide\project\supports;

use develnext\lexer\inspector\PHPInspector;
use ide\project\AbstractProjectSupport;
use ide\project\Project;
use ide\project\ProjectFile;
use ide\utils\FileUtils;
use php\lang\Thread;
use php\lib\fs;
use php\lib\str;

class PHPProjectSupport extends AbstractProjectSupport
{
    /**
     * @var PHPInspector
     */
    protected $inspector;

    /**
     * @return PHPInspector
     */
    public function getInspector(): PHPInspector
    {
        return $this->inspector;
    }

    public function getCode()
    {
        return 'php';
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function isFit(Project $project)
    {
        return $project->getFile("package.php.yml")->isFile()
            || $project->getFile("composer.json")->isFile();
    }

    protected function getProjectPackage(Project $project)
    {
        $package = ['classes' => [], 'functions' => [], 'constants' => []];

        $dirs = [];

        if ($project->getSrcDirectory() !== null) {
            $dirs[] = $project->getSrcFile('');
        }

        if ($project->getSrcGeneratedDirectory() !== null) {
            $dirs[] = $project->getSrcFile('', true);
        }

        foreach ($dirs as $directory) {
            fs::scan($directory, function ($filename) use ($directory, &$package) {
                if (fs::ext($filename) == 'php') {
                    $classname = FileUtils::relativePath($directory, $filename);

                    if ($classname[0] == '.') {
                        return;
                    }

                    $classname = fs::pathNoExt($classname);
                    $classname = str::replace($classname, '/', '\\');
                    $package['classes'][] = $classname;
                }
            });
        }

        return $package;
    }

    protected function refreshInspector(Project $project)
    {
        if ($this->inspector) {
            $this->inspector->setExtensions(['php']);

            (new Thread(function () use ($project) {
                $package = $this->getProjectPackage($project);
                $this->inspector->putPackage($project->getPackageName(), $package);
                $this->inspector->loadDirectory($project->getRootDir());
            }))->start();
        }
    }

    /**
     * @param Project $project
     * @return mixed|void
     */
    public function onLink(Project $project)
    {
        $this->inspector = new PHPInspector();
        //$this->inspector->loadDirectory($project->getRootDir());
        $project->registerInspector('php', $this->inspector);

        $project->on('open', function () use ($project) {
            $tree = $project->getTree();
            $tree->addIgnoreExtensions([
                'source', 'sourcemap'
            ]);

            $tree->addIgnorePaths(['src_generated']);

            $project->eachSrcFile(function (ProjectFile $file) {
                if (str::endsWith($file, '.php.source')) {
                    FileUtils::copyFileAsync($file, fs::pathNoExt($file));
                    fs::delete($file);
                }
            });

            $project->clearIdeCache('bytecode');

            $this->refreshInspector($project);
        }, __CLASS__);
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function onUnlink(Project $project)
    {
        $project->unregisterInspector('php');

        $project->offGroup(__CLASS__);
        $this->inspector->free();
        $this->inspector = null;

        $tree = $project->getTree();
        $tree->removeIgnoreExtensions([
            'source', 'sourcemap'
        ]);
        $tree->removeIgnorePaths(['src_generated']);
    }
}