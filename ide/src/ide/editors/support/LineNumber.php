<?php

namespace ide\editors\support;

use php\gui\layout\UXHBox;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXLabel;

class LineNumber
{
    /**
     * @var UXImageView[]
     */
    private $gutters;
    
    public function __invoke(int $line) {
        $node = new UXLabel($line + 1);
        $node->paddingRight = 2;
        $node->classes->add("lineno");

        foreach ([10, 100, 1000, 10000, 100000] as $i)
            if ($line + 1 < $i) $node->paddingRight += 7;

        if (!$this->gutters[$line + 1])
            $gutter = &$this->gutters[$line + 1] = new UXImageView();
        else $gutter = &$this->gutters[$line + 1];
        
        $gutter->size = [16, 16];
        $gutter->cursor = "HAND";
        
        $box = new UXHBox([$gutter, $node]);
        $box->classes->add("gutter");
        $box->spacing = $box->paddingLeft = 8;
        return $box;
    }

    /**
     * @param int $line
     * @param Gutter $gutter
     */
    public function addGutter(int $line, Gutter $gutter) {
        if (!$this->gutters[$line]) $this->gutters[$line] = new UXImageView();
        
        $this->gutters[$line]->image = $gutter->getImage();
        $this->gutters[$line]->on("click", $gutter->getCallback());
    }

    /**
     * @param int $line
     */
    public function removeGutter(int $line) {
        if (!$this->gutters[$line]) return;

        $this->gutters[$line]->image = null;
        $this->gutters[$line]->off("click");
    }
}