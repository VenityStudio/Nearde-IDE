<?php

namespace ide\settings;

use php\gui\UXNode;

abstract class AbstractSettings
{
    /**
     * @return string
     */
    abstract public function getName() : string;

    /**
     * @return string
     */
    abstract public function getIcon16() : string;

    /**
     * @return UXNode
     */
    abstract public function getNode() : UXNode;

    abstract public function save() : void;

    abstract public function toDefault() : void;
}