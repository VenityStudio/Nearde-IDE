<?php
namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\OpenProjectForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use php\gui\UXMenuItem;
use php\gui\UXSplitMenuButton;

/**
 * Class OpenProjectCommand
 * @package ide\commands
 */
class OpenProjectCommand extends AbstractCommand
{
    public function getName()
    {
        return 'menu.project.open';
    }

    public function getIcon()
    {
        return 'icons/open16.png';
    }

    public function getAccelerator()
    {
        return 'Ctrl + Alt + O';
    }

    public function isAlways()
    {
        return true;
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        static $dialog = null;

        if (!$dialog) {
            $dialog = new OpenProjectForm();
            $dialog->owner = Ide::get()->getMainForm();
        }

        $dialog->showAndWait();
    }
}