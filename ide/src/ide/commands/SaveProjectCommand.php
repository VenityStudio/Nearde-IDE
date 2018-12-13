<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;

/**
 * Class SaveProjectCommand
 * @package ide\commands
 */
class SaveProjectCommand extends AbstractProjectCommand
{
    public function getName()
    {
        return 'menu.project.save';
    }

    public function getIcon()
    {
        return 'icons/save16.png';
    }

    public function getAccelerator()
    {
        return 'Ctrl + S';
    }

    public function isAlways()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $project = Ide::get()->getOpenedProject();

        if ($project) {
            $project->save();

            Ide::get()->getMainForm()->toast(_('toast.project.save.done'));
        }
    }
}