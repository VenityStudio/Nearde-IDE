<?php

namespace ide\settings;


use php\gui\UXLabel;
use php\gui\UXNode;

class IdeSettings extends AbstractSettings
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
    public function getIcon16(): string
    {
        return "icons/edit16.png";
    }

    /**
     * @return UXNode
     */
    public function getNode(): UXNode
    {
        return new UXLabel("Soon ....");
    }

    public function save(): void
    {
        alert("save");
    }

    public function toDefault(): void
    {
        alert("def");
    }
}