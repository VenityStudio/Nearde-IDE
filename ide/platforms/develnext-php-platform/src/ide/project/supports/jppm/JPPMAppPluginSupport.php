<?php
namespace ide\project\supports\jppm;

use framework\core\Event;
use ide\formats\ProjectFormat;
use ide\Ide;
use ide\project\AbstractProjectSupport;
use ide\project\Project;
use ide\project\supports\JPPMProjectSupport;
use ide\systems\IdeSystem;
use ide\systems\ProjectSystem;
use ide\utils\Json;
use php\concurrent\Promise;
use php\lang\Process;
use php\lib\arr;
use Throwable;

class JPPMAppPluginSupport extends AbstractProjectSupport
{
    public function getCode()
    {
        return 'jppm-app-plugin';
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function isFit(Project $project)
    {
        /** @var JPPMProjectSupport $jppm */
        if ($project->hasSupport('jppm')) {
            $jppm = $project->findSupport('jppm');
            return arr::has($jppm->getPkgTemplate()->getPlugins(), 'App');
        } else {
            return false;
        }
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

        $prepareFunc = function ($output): Promise {
            return new Promise(function ($resolve, $reject) use ($output) {
                try {
                    ProjectSystem::compileAll(Project::ENV_DEV, $output, "Prepare project ...", function () use ($resolve) {
                        $resolve(true);
                    });
                } catch (Throwable $e) {
                    $reject($e);
                }
            });
        };

        $project->getRunDebugManager()->add('jppm-start', [
            'title' => 'jppm.tasks.start.title',
            'prepareFunc' => $prepareFunc,
            'makeStartProcess' => function () use ($project) {
                $env = Ide::get()->makeEnvironment();

                $args = ['jppm', 'start'];

                if (Ide::get()->isWindows()) {
                    $args = flow(['cmd', '/c'], $args)->toArray();
                }

                $process = new Process($args, $project->getRootDir(), $env);
                return $process;
            },
        ]);

        $project->getRunDebugManager()->add('jppm-build', [
            'title' => 'jppm.tasks.build.title',
            'prepareFunc' => $prepareFunc,
            'icon' => 'icons/boxArrow16.png',
            'makeStartProcess' => function () use ($project) {
                $env = Ide::get()->makeEnvironment();

                $args = ['jppm', 'build'];

                if (Ide::get()->isWindows()) {
                    $args = flow(['cmd', '/c'], $args)->toArray();
                }

                $process = new Process($args, $project->getRootDir(), $env);
                return $process;
            },
        ]);

        $tasksFile = $project->getIdeFile("tasks.json");

        foreach (Json::fromFile($tasksFile) as $item => $value)
        {
            $project->getRunDebugManager()->add($item, [
                'title' => $value['title'] ?? $item,
                'icon' => "icons/gear16.png",
                'makeStartProcess' => function () use ($project, $value) {
                    $env = Ide::get()->makeEnvironment();

                    foreach ($value['env'] as $key => $val)
                        if (is_string($val))
                            $env[$key] = $val;

                    switch ($value['type']) {
                        case "jppm":
                            $args = ['jppm', $value['task']];
                            break;

                        default:
                            $args = $value['command'];
                    }

                    if (Ide::get()->isWindows())
                        $args = flow(['cmd', '/c'], $args)->toArray();

                    return new Process($args, $project->getRootDir(), $env);
                },
            ]);
        }

        $project->getRunDebugManager()->setStarter("jppm-start");
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

        $project->getRunDebugManager()->remove('jppm-start');
        $project->getRunDebugManager()->remove('jppm-build');
        $project->getRunDebugManager()->setStarter(null);
    }
}