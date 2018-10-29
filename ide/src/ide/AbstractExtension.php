<?php
namespace ide;

abstract class AbstractExtension
{
    abstract public function getName() : string;
    abstract public function getAuthor() : string;
    abstract public function getVersion() : string;
    abstract public function getIcon32() : string;

    abstract public function onRegister();
    abstract public function onIdeStart();
    abstract public function onIdeShutdown();

    /**
     * @return string[] classes of AbstractExtension or AbstractBundle
     */
    public function getDependencies()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isSystem() : bool {
        return false;
    }
}