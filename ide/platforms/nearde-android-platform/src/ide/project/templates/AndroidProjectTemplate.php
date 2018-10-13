<?php

namespace ide\project\templates;


use ide\formats\ProjectFormat;
use ide\forms\CreateAndroidProjectForm;
use ide\project\AbstractProjectTemplate;
use ide\project\behaviours\BackupProjectBehaviour;
use ide\project\behaviours\JavaPlatformBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;

class AndroidProjectTemplate extends AbstractProjectTemplate
{

    public function getName()
    {
        return "JPHP Android";
    }

    public function getDescription()
    {
        return "JPHP for Android";
    }

    public function getIcon32()
    {
        return "icons/android32.png";
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function makeProject(Project $project)
    {
        return (new CreateAndroidProjectForm($project))->showAndWait();
    }

    /**
     * @param Project $project
     * @return mixed
     */
    public function recoveryProject(Project $project)
    {
        if (!$project->hasBehaviour(JavaPlatformBehaviour::class)) {
            $project->register(new JavaPlatformBehaviour(), false);
        }

        if (!$project->hasBehaviour(PhpProjectBehaviour::class)) {
            $project->register(new PhpProjectBehaviour(), false);
        }

        if (!$project->hasBehaviour(BackupProjectBehaviour::class)) {
            $project->register(new BackupProjectBehaviour(), false);
        }

        if (!$project->getRegisteredFormat(ProjectFormat::class)) {
            $project->registerFormat(new ProjectFormat());
        }
    }
}