<?php

namespace ide;

use ide\project\support\AndroidProjectSupport;
use ide\project\templates\AndroidProjectTemplate;

class AndroidExtension extends AbstractExtension
{

    public function onRegister()
    {
        Ide::get()->registerProjectTemplate(new AndroidProjectTemplate());
        Ide::get()->registerProjectSupport(new AndroidProjectSupport());
    }

    public function onIdeStart()
    {

    }

    public function onIdeShutdown()
    {

    }
}