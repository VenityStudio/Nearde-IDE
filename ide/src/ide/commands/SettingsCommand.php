<?php

namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\IdeSettingsForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\themes\DarkTheme;

class SettingsCommand extends AbstractCommand
{
    public function getName()
    {
        return "common.settings";
    }

    public function getIcon()
    {
        return "icons/gear16.png";
    }

    public function getCategory()
    {
        return "help";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $form = new IdeSettingsForm();
        $form->showAndWait();
    }

    public function isAlways()
    {
        return true;
    }
}