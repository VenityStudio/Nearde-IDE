<?php

namespace ide\editors\support;

use ide\utils\FileUtils;
use php\gui\UXStyleClassedTextArea;
use php\gui\UXVirtualizedScrollPane;

class CodeArea extends UXVirtualizedScrollPane
{
    /**
     * @var UXStyleClassedTextArea
     */
    protected $richArea;

    /**
     * @var AbstractHighlighter
     */
    protected $highlighter;

    /**
     * @var LineNumber
     */
    protected $lineNumber;

    public function __construct()
    {
        parent::__construct($this->richArea = new UXStyleClassedTextArea());

        $this->richArea->setGraphicFactory($this->lineNumber = new LineNumber());

        $this->richArea->classes->add("syntax-text-area");
    }

    public function refreshLineNumber() : void {
        $this->richArea->clearGraphicFactory();
        $this->richArea->setGraphicFactory($this->lineNumber);
    }

    /**
     * @return UXStyleClassedTextArea
     */
    public function getRichArea(): UXStyleClassedTextArea
    {
        return $this->richArea;
    }

    public function setHighlighter(string $class) {
        $this->highlighter = new $class($this);
        $this->highlighter->apply();
    }

    /**
     * @return AbstractHighlighter
     */
    public function getHighlighter(): ?AbstractHighlighter
    {
        return $this->highlighter;
    }

    /**
     * @param string $file
     */
    public function addStylesheet(string $file) {
        $this->richArea->stylesheets->add(FileUtils::urlPath($file));
    }

    /**
     * @return LineNumber
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }
}