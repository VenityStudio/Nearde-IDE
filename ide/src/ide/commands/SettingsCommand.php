<?php

namespace ide\commands;

use ide\editors\AbstractEditor;
use ide\forms\AbstractIdeForm;
use ide\forms\IdeSettingsForm;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\themes\DarkTheme;

class SettingsCommand extends AbstractCommand
{
    /**
     * @var AbstractIdeForm
     */
    private $form;


    public function __construct()
    {
        parent::__construct();

        $this->form = new IdeSettingsForm();
    }

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
        if (!$this->form->visible)
            $this->form->showAndWait();
        else $this->form->toFront();
    }

    public function isAlways()
    {
        return true;
    }
}