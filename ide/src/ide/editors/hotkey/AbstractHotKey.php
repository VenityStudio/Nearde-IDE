<?php

namespace ide\editors\hotkey;


use ide\editors\support\CodeArea;
use php\gui\event\UXKeyEvent;

abstract class AbstractHotKey
{
    /**
     * @return string
     */
    abstract public function getAccelerator(): string;

    /**
     * @param CodeArea $area
     * @param UXKeyEvent $event
     * @return void
     */
    abstract public function execute(CodeArea $area, UXKeyEvent $event): void;
}