<?php

namespace ide\editors\hotkey;


use ide\editors\support\CodeArea;
use php\gui\event\UXKeyEvent;

class AutoBracketHotKey extends AbstractHotKey
{
    /**
     * @return string
     */
    public function getAccelerator(): string
    {
        return null;
    }

    /**
     * @param CodeArea $area
     * @param UXKeyEvent $event
     * @return void
     */
    public function execute(CodeArea $area, UXKeyEvent $event): void
    {
        foreach ([
            "{" => "}",
            "[" => "]",
            "(" => ")"
                 ] as $open => $close) {
            if ($event->text == $open) {
                $pos = $area->getRichArea()->caretPosition;
                $area->getRichArea()->appendText($close);
                $area->getRichArea()->selectRange($pos, $pos);

                break;
            }
        }
    }
}