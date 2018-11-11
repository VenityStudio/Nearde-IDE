<?php

namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\themes\DarkTheme;
use ide\forms\AboutIdeForm;

class AboutCommand extends AbstractCommand
{

    public function getName()
    {
        return "common.about";
    }

    public function getIcon()
    {
        return "icons/information16.png";
    }

    public function getCategory()
    {
        return "help";
    }

    public function getAccelerator()
    {
        return 'F1';
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        $about = new AboutIdeForm();
        $about->show();
    }

    public function isAlways()
    {
        return true;
    }
}