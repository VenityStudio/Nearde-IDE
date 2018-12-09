<?php

namespace ide\forms;

use ide\Ide;
use php\gui\layout\UXVBox;
use php\gui\UXButton;
use php\gui\UXImageView;
use php\gui\UXLabel;

/**
 * Class MainWindowForm
 * @package ide\forms
 *
 * @property UXVBox root
 * @property UXVBox top_root
 * @property UXVBox buttons_root
 * @property UXImageView ide_logo
 * @property UXLabel ide_name
 * @property UXLabel ide_version
 */
class MainWindowForm extends AbstractIdeForm
{
    public function __construct()
    {
        parent::__construct();

        $this->ide_logo->image   = Ide::getImage(Ide::get()->getPlatform()->getIDEIcon())->image;
        $this->ide_name->text    = $this->title = Ide::get()->getPlatform()->getIDEName();
        $this->ide_version->text = Ide::get()->getPlatform()->getIDEVersion();

        $this->layout = _($this->layout);

        $this->minWidth  = $this->maxWidth  = $this->width = 680;
        $this->minHeight = $this->maxHeight = $this->height = 450;

        $this->resizable = false;

        foreach (Ide::get()->getMainWindowButtons() as $mainWindowButton) {
            $button = new UXButton($mainWindowButton->getName());
            $button->width = 200;
            $button->graphic = Ide::getImage($mainWindowButton->getIcon(), [16, 16]);
            $button->on("click", [$mainWindowButton, "onAction"]);

            $this->buttons_root->add(_($button));
        }

        Ide::get()->bind("openProject", function () {
            Ide::get()->getMainForm()->show();
            $this->hide();
        });

        Ide::get()->bind("closeProject", function () {
            Ide::get()->getMainForm()->hide();
            $this->show();
        });
    }
}