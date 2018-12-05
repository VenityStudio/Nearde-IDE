<?php

namespace ide;


abstract class AbstractPlatform
{
    /**
     * @return string
     */
    abstract public function getIDEName(): string;

    /**
     * @return string
     */
    abstract public function getIDEVersion(): string;

    /**
     * @return string
     */
    abstract public function getIDEIcon(): string;

    abstract public function onIdeStart();
    abstract public function onIdeShutdown();
}