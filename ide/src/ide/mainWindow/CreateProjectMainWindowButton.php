<?php

namespace ide\mainWindow;


use ide\AbstractMainWindowButton;
use ide\commands\NewProjectCommand;
use ide\Ide;

class CreateProjectMainWindowButton extends AbstractMainWindowButton
{

    /**
     * @return string
     */
    public function getName(): string
    {
        return "welcome.project.create";
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return "icons/greenDocument16.png";
    }

    public function onAction(): void
    {
        Ide::get()->executeCommand(NewProjectCommand::class);
    }
}