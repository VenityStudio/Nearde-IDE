<?php

namespace ide\editors\hotkey;


use ide\editors\support\CodeArea;
use php\gui\event\UXKeyEvent;
use php\lib\arr;

class EnterHotKey extends AbstractHotKey
{
    /**
     * @return string
     */
    public function getAccelerator(): string
    {
        return "Enter";
    }

    /**
     * @param CodeArea $area
     * @param UXKeyEvent $event
     * @return void
     */
    public function execute(CodeArea $area, UXKeyEvent $event): void
    {
        $pos = $area->getRichArea()->caretPosition;

        $lastChar = trim($area->getRichArea()->getTextOfPosition($pos - 2, $pos - 1));

        if (arr::has([ "{", "(", "[" ], $lastChar)) {
            $area->getRichArea()->replaceText($pos - 1, $pos, "\n\t\n");
            $area->getRichArea()->selectRange($pos + 1, $pos + 1);
        }
    }
}