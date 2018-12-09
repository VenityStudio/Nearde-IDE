<?php

namespace ide;


abstract class AbstractMainWindowButton
{
    /**
     * @return string
     */
    abstract public function getName(): string;

    /**
     * @return string
     */
    abstract public function getIcon(): string;

    abstract public function onAction(): void;
}