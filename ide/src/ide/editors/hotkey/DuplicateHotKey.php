<?php

namespace ide\editors\hotkey;


use ide\editors\support\CodeArea;
use php\gui\event\UXKeyEvent;
use php\lib\str;

class DuplicateHotKey extends AbstractHotKey
{
    /**
     * @return string
     */
    public function getAccelerator(): string
    {
        return "Ctrl + D";
    }

    /**
     * @param CodeArea $area
     * @param UXKeyEvent $event
     * @return void
     */
    public function execute(CodeArea $area, UXKeyEvent $event): void
    {
        $s = $area->getRichArea()->selection;
        $selected = $area->getRichArea()->getTextOfPosition($s["start"], $s["end"]);
        if (!$selected) return;

        $area->getRichArea()->replaceText($s["start"], $s["end"], str::join([$selected, $selected], "\n"));
        $area->getRichArea()->selectRange($s["start"] + $s["length"] + 1, $s["end"] + $s["length"] + 1);
    }
}