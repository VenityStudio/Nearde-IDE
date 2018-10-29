<?php
namespace ide\javaplatform;

use ide\AbstractExtension;

class JavaPlatformExtension extends AbstractExtension
{
    public function onRegister()
    {

    }

    public function onIdeStart()
    {

    }

    public function onIdeShutdown()
    {

    }

    public function getName(): string
    {
        return "JavaPlatform";
    }

    public function getAuthor(): string
    {
        return "jPHP Group";
    }

    public function getVersion(): string
    {
        return "17.0.0";
    }

    public function getIcon32(): string
    {
        return null;
    }

    public function isSystem(): bool
    {
        return false;
    }
}