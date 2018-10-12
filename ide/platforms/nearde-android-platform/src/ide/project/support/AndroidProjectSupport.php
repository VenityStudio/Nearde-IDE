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
use php\concurrent\Promise;
use php\lang\Process;
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

        $project->getRunDebugManager()->remove('jppm-start');
        $project->getRunDebugManager()->remove('jppm-build');

        $project->getRunDebugManager()->add('jppm-android-build', [
            'title' => 'Build APK',
            'prepareFunc' => $prepareFunc,
            'icon' => 'icons/boxArrow16.png',
            'makeStartProcess' => function () use ($project) {
                $env = Ide::get()->makeEnvironment();

                $args = ['jppm', 'android:compile', '-build'];

                if (Ide::get()->isWindows()) {
                    $args = flow(['cmd', '/c'], $args)->toArray();
                }

                $process = new Process($args, $project->getRootDir(), $env);
                return $process;
            },
        ]);
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

        $project->getRunDebugManager()->remove('jppm-android-build');
    }
}