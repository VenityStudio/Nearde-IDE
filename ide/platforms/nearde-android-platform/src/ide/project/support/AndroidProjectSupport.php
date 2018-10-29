<?php

namespace ide\project\support;


use ide\formats\ProjectFormat;
use ide\Ide;
use ide\project\AbstractProjectSupport;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;
use ide\project\supports\jppm\JPPMControlPane;
use ide\project\templates\AndroidProjectTemplate;
use ide\systems\ProjectSystem;
use ide\utils\Json;
use php\concurrent\Promise;
use php\io\Stream;
use php\lang\Process;
use php\lib\fs;
use Throwable;

class AndroidProjectSupport extends AbstractProjectSupport
{

    /**
     * @param Project $project
     * @return mixed
     */
    public function isFit(Project $project)
    {
        return ($project->hasBehaviour(PhpProjectBehaviour::class)
            || $project->getFile("package.php.yml")->isFile()) && $project->getTemplate() instanceof AndroidProjectTemplate;
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function onLink(Project $project)
    {
        /** @var ProjectFormat $projectFormat */
        if ($projectFormat = $project->getRegisteredFormat(ProjectFormat::class)) {
            $projectFormat->addControlPane(new JPPMControlPane());
        }

        $project->getRunDebugManager()->remove('jppm-start');
        $project->getRunDebugManager()->remove('jppm-build');
        $project->getRunDebugManager()->setStarter("gradle-packageDebug");

        $tasksFile = $project->getIdeFile("tasks.json");

        if (!fs::exists($tasksFile)) {
            fs::makeFile($tasksFile->getAbsolutePath());
            Stream::putContents($tasksFile->getAbsolutePath(), Json::encode([
                "gradle-packageDebug" => [
                    "type" => "android",
                    "task" => "packageDebug",
                    "title" => "packageDebug task"

                ],
                "jppm-update" => [
                    "type" => "jppm",
                    "task" => "update",
                    "title" => "jppm update"
                ]
            ]));
        }


        $tasks = Json::fromFile($tasksFile->getAbsolutePath());

        foreach ($tasks as $id => $array) {
            $project->getRunDebugManager()->add($id, [
                'title' => $array['title'] ?? $id,
                'icon' => $array['type'] == "gradle" ? "icons/gradle16.png" :
                    $array['type'] == "android" ? "icons/gradleAndroid16.png" : "icons/gear16.png",
                'makeStartProcess' => function () use ($project, $array) {
                    $env = Ide::get()->makeEnvironment();

                    foreach ($array['env'] as $key => $val)
                        if (is_string($val))
                            $env[$key] = $val;

                    switch ($array['type']) {
                        case "android":
                            $args = ['jppm', 'android:compile', "-{$array["task"]}"];
                            break;

                        case "jppm":
                            $args = ['jppm', $array['task']];
                            break;

                        default:
                            $args = $array['command'];
                    }

                    if (Ide::get()->isWindows())
                        $args = flow(['cmd', '/c'], $args)->toArray();

                    return new Process($args, $project->getRootDir(), $env);
                },
            ]);
        }
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function onUnlink(Project $project)
    {
        /** @var ProjectFormat $projectFormat */
        if ($projectFormat = $project->getRegisteredFormat(ProjectFormat::class)) {
            $projectFormat->removeControlPane(JPPMControlPane::class);
        }

        $project->getRunDebugManager()->clear();
        $project->getRunDebugManager()->setStarter(null);
    }
}