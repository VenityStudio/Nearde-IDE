<?php

namespace ide\mainWindow;

use ide\AbstractMainWindowButton;
use ide\commands\SettingsCommand;
use ide\Ide;

class SettingsMainWindowButton extends AbstractMainWindowButton
{

    /**
     * @return string
     */
    public function getName(): string
    {
        return "ide.settings.ide";
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return "icons/settings16.png";
    }

    public function onAction(): void
    {
        Ide::get()->executeCommand(SettingsCommand::class);
    }
}