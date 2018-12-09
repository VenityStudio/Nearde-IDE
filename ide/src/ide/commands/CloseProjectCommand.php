<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;

/**
 * Class CloseProjectCommand
 * @package ide\commands
 */
class CloseProjectCommand extends AbstractProjectCommand
{
    public function getName()
    {
        return 'menu.project.close';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        ProjectSystem::close(false);

        Ide::get()->getMainForm()->hide();
        Ide::get()->getMainWindow()->show();
    }

    public function withBeforeSeparator()
    {
        return true;
    }

    public function isAlways()
    {
        return true;
    }
}