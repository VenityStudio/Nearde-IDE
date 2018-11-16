<?php

namespace ide\settings;

use ide\Ide;
use ide\themes\LightTheme;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXComboBox;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXSeparator;

class IdeSettings extends AbstractSettings
{
    /**
     * @var UXComboBox
     */
    private $themesList;

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
        $box = new UXVBox();
        $box->add($hb = new UXHBox([
            new UXLabel(_("ide.settings.ide.themes")), $this->themesList = new UXComboBox()
        ]));

        $box->spacing = $box->padding = $hb->spacing = 8;
        $hb->alignment = "CENTER_LEFT";
        $hb->maxHeight = 150;
        $box->add(new UXSeparator());

        $this->themesList->value = Ide::get()->getThemeManager()->getDefault()->getName();
        foreach (Ide::get()->getThemeManager()->getAll() as $theme) $this->themesList->items->add($theme->getName());

        return $box;
    }

    public function save(): void
    {
        if ($this->themesList->value)
            Ide::get()->setUserConfigValue("ide.theme", $this->themesList->value);

        if (uiConfirm(_("ide.settings.restart.question"))) Ide::get()->restart();
    }

    public function toDefault(): void
    {
        Ide::get()->setUserConfigValue("ide.theme", (new LightTheme())->getName());

        if (uiConfirm(_("ide.settings.restart.question"))) Ide::get()->restart();
    }
}