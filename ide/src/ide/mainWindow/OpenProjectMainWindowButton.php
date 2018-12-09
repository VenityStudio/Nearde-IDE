<?php

namespace ide\mainWindow;


use ide\AbstractMainWindowButton;
use ide\commands\OpenProjectCommand;
use ide\Ide;

class OpenProjectMainWindowButton extends AbstractMainWindowButton
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return "welcome.project.open";
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return "icons/open16.png";
    }

    public function onAction(): void
    {
        Ide::get()->executeCommand(OpenProjectCommand::class);
    }
}