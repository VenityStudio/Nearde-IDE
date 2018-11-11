<?php
namespace ide\forms;

use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\themes\DarkTheme;

class AboutIdeForm extends AbstractIdeForm
{
	public function init()
    {
    	$this->modality = 'APPLICATION_MODAL';
    }

	/**
     * @event show 
     */
    function doShow()
    {    
        $this->title = "Nearde IDE";
        $this->info->text = "0.1 (SNAPSHOT)\nVenity & jPHP group\n\nContibutors:\n- dim-s\n- MWGuy\n- gbowsky";
    }

}