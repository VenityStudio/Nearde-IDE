<?php

namespace ide;

use ide\project\support\AndroidProjectSupport;
use ide\project\templates\AndroidProjectTemplate;

class AndroidExtension extends AbstractExtension
{

    /**
     * @throws IdeException
     * @throws \Exception
     */
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

    public function getName(): string
    {
        return "Android";
    }

    public function getAuthor(): string
    {
        return "Venity Group";
    }

    public function getVersion(): string
    {
        return "0.1.0";
    }

    public function getIcon32(): string
    {
        return "icons/android32.png";
    }
}