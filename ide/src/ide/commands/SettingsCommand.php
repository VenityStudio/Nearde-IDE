<?php

namespace ide\commands;

use ide\editors\AbstractEditor;
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
        //Ide::get()->setUserConfigValue("ide.theme", (new DarkTheme())->getName());
        //Ide::get()->restart();
        Ide::toast("Soon ...");
    }

    public function isAlways()
    {
        return true;
    }
}