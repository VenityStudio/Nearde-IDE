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
        $node->alignment = "BASELINE_RIGHT";
        $node->paddingRight = 10;
        $node->classes->add("lineno");
	$node->width = 50;

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