<?php

namespace ide\forms;

use ide\Ide;
use ide\settings\AbstractSettings;
use php\gui\UXButton;
use php\gui\UXTab;
use php\gui\UXTabPane;

/**
 * Class IdeSettingsForm
 * @package ide\forms
 *
 * @property UXTabPane setting_tabs
 * @property UXButton def_btn
 * @property UXButton close_btn
 * @property UXButton save_btn
 */
class IdeSettingsForm extends AbstractIdeForm
{
    /**
     * @var AbstractSettings[]
     */
    private $settings;


    /**
     * IdeSettingsForm constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->title = _("common.settings");
        $this->settings = Ide::get()->getSettingsContainer()->getAll();

        foreach ($this->settings as $setting) {
            $tab = new UXTab();
            $tab->data("setting.name", $setting->getName());
            $tab->text = _($setting->getName());
            $tab->content = $setting->getNode();
            $tab->graphic = Ide::getImage($setting->getIcon16(), [16, 16]);

            $this->setting_tabs->tabs->add($tab);
        }

        // l10n for buttons
        _($this->save_btn);
        _($this->def_btn);
        _($this->close_btn);
    }

    /**
     * @event def_btn.click
     * @throws \Exception
     */
    public function doDefaultClick() {
        uiConfirm(_("ide.settings.default.question")) == true ?
            $this->settings[$this->setting_tabs->selectedTab->data("setting.name")]->toDefault() : null;
    }

    /**
     * @event save_btn.click
     * @throws \Exception
     */
    public function doSaveClick() {
        uiConfirm(_("ide.settings.save.question")) == true ?
            $this->settings[$this->setting_tabs->selectedTab->data("setting.name")]->save() : null;
    }

    /**
     * @event close_btn.click
     */
    public function doCloseClick() {
        $this->hide();
    }
}