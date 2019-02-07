<?php

namespace ide\editors\support;

use ide\misc\EventHandlerBehaviour;
use php\lib\str;

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
        // nup.
    }

    abstract public function applyHighlight() : void;

    /**
     * Clear all styles from CodeArea
     */
    public function clearCodeAreaStyle() {
        $this->codeArea->getRichArea()->clearStyle(0, str::length($this->codeArea->getRichArea()->text));
    }
}