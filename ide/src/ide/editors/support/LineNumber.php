<?php

namespace ide\editors\support;

use php\gui\layout\UXVBox;
use php\gui\UXLabel;

class LineNumber
{
    public function __invoke(int $line) {
        $node = new UXLabel($line + 1);
        $node->paddingRight = 10;
        $node->paddingLeft = 8;
        $node->classes->add("lineno");

        foreach ([10, 100, 1000, 10000] as $i)
            if ($line + 1 < $i) $node->paddingRight += 7;

        $box = new UXVBox([$node]);
        $box->classes->add("gutter");
        return $box;
    }
}