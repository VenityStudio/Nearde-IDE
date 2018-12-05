<?php
namespace ide\forms;

use ide\Ide;
use php\gui\layout\UXHBox;
use php\gui\UXHyperlink;
use php\gui\UXImageView;
use php\gui\UXLabel;

/**
 * Class AboutIdeForm
 * @package ide\forms
 *
 * @property UXImageView logo
 * @property UXLabel ide_name
 * @property UXLabel about
 * @property UXHBox link_box
 * @property UXHyperlink gitlink
 */
class AboutIdeForm extends AbstractIdeForm
{
    /**
     * @event show 
     */
    public function doShow()
    {    
        $this->ide_name->text = Ide::get()->getName() . " " . Ide::get()->getVersion();
        $this->logo->image = Ide::getImage(Ide::get()->getIcon(), [100, 100])->image;
        $this->about->text = "Venity & jPHP group\n\nContibutors:\ndim-s\nMWGuy\ngbowsky";

        $this->gitlink->on("click", fn() => browse("https://github.com/VenityStudio/Nearde-IDE"));
    }

}