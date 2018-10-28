<?php

namespace ide\editors;


use ide\editors\highlighters\JsonHighlighter;
use ide\editors\support\CodeArea;
use ide\editors\support\Gutter;
use ide\Ide;
use php\gui\UXNode;
use php\io\Stream;
use php\lib\fs;
use php\lib\str;

class TextEditor extends AbstractEditor
{
    /**
     * @var CodeArea
     */
    private $editor;

    /**
     * TextEditor constructor.
     * @param $file
     * @throws \Exception
     */
    public function __construct($file)
    {
        parent::__construct($file);
        
        $this->editor = new CodeArea();
        $this->editor->addStylesheet(Ide::get()->getThemeManager()->getDefault()->getCodeEditorCssFile());
        
        switch (fs::ext($file)) {
            case "json":
                $this->editor->setHighlighter(JsonHighlighter::class);
                break;
        }
    }

    public function load()
    {
        $this->editor->getRichArea()->appendText(Stream::getContents($this->getFile()));

        if ($this->editor->getHighlighter())
            $this->editor->getHighlighter()->applyHighlight();
    }

    public function save()
    {
        Stream::putContents($this->getFile(), $this->editor->getRichArea()->text);
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        return $this->editor;
    }

    /**
     * @return CodeArea
     */
    public function getEditor(): CodeArea
    {
        return $this->editor;
    }
}