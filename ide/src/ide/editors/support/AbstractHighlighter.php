<?php

namespace ide\editors\support;

use ide\misc\EventHandlerBehaviour;

abstract class AbstractHighlighter
{
    use EventHandlerBehaviour;

    /**
     * @var CodeArea
     */
    protected $codeArea;

    public function __construct(CodeArea $area)
    {
        $this->codeArea = $area;
    }

    public function apply() {
        $this->codeArea->getRichArea()->on("keyUp", function () {
            $this->applyHighlight();
            $this->trigger("applyHighlight");
        });
    }

    abstract public function applyHighlight() : void;
}