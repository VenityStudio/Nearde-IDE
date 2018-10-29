<?php
namespace ide;

use ide\systems\DialogSystem;

class IdeStandardExtension extends AbstractExtension
{
    public function onRegister()
    {
        DialogSystem::registerDefaults();
    }

    public function onIdeStart()
    {

    }

    public function onIdeShutdown()
    {

    }

    public function getName(): string
    {
        return Ide::get()->getName();
    }

    public function getAuthor(): string
    {
        return "jPHP Group & Venity Group";
    }

    public function getVersion(): string
    {
        return Ide::get()->getVersion();
    }

    public function getIcon32(): string
    {
        return null;
    }

    public function isSystem(): bool
    {
        return true;
    }
}