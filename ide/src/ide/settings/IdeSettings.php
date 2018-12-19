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
     * @var UXComboBox
     */
    private $languagesList;

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
     * @throws \Exception
     */
    public function getNode(): UXNode
    {
        $box = new UXVBox();
        $box->add($hb = new UXHBox([
            _(new UXLabel("ide.settings.ide.themes")), $this->themesList = new UXComboBox()
        ]));

        $box->spacing = $box->padding = $hb->spacing = 8;
        $hb->alignment = "CENTER_LEFT";
        $hb->maxHeight = 150;
        $box->add(new UXSeparator());

        $this->themesList->value = Ide::get()->getThemeManager()->getDefault()->getName();
        foreach (Ide::get()->getThemeManager()->getAll() as $theme) $this->themesList->items->add($theme->getName());

        $box->add($hb = new UXHBox([
            _(new UXLabel("ide.settings.ide.language")), $this->languagesList = new UXComboBox()
        ]));

        $box->spacing = $box->padding = $hb->spacing = 8;
        $hb->alignment = "CENTER_LEFT";
        $hb->maxHeight = 150;
        $box->add(new UXSeparator());

        if ($l = Ide::get()->getLanguage())
            $this->languagesList->value = $l;

        foreach (Ide::get()->getLanguages() as $language)
            $this->languagesList->items->add($language);

        return $box;
    }

    public function save(): void
    {
        $oldTheme = Ide::get()->getUserConfigValue("ide.theme");

        if ($this->themesList->value)
            Ide::get()->setUserConfigValue("ide.theme", $this->themesList->value);

        Ide::get()->setUserConfigValue('ide.language', $this->languagesList->value->getCode());
        Ide::get()->getLocalizer()->language = $this->languagesList->value->getCode();


        if ($oldTheme != $this->themesList->value)
            if (uiConfirm(_("ide.settings.restart.question"))) Ide::get()->restart();
    }

    public function toDefault(): void
    {
        $this->themesList->value = (new LightTheme())->getName();
        $this->languagesList->value = $this->languagesList->items->toArray()[0];

        $this->save();
    }
}