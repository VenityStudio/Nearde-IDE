<?php

namespace ide\editors;

use ide\editors\highlighters\JsonHighlighter;
use ide\editors\highlighters\MarkDownHighlighter;
use ide\editors\hotkey\AbstractHotKey;
use ide\editors\support\CodeArea;
use ide\Ide;
use php\gui\event\UXKeyEvent;
use php\gui\UXNode;
use php\io\Stream;
use php\lib\fs;

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

        $this->editor->getRichArea()->on("keyUp", function (UXKeyEvent $event) {
            foreach (static::$hotkeys as $hotKey)
                if ($hotKey->getAccelerator() == null || $event->matches($hotKey->getAccelerator()))
                    $hotKey->execute($this->editor, $event);

            if ($this->editor->getHighlighter()) {
                $this->editor->getHighlighter()->applyHighlight();
                $this->editor->getHighlighter()->trigger("applyHighlight");
            }
        });
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

    /**
     * @var AbstractHotKey[]
     */
    private static $hotkeys;

    /**
     * @param AbstractHotKey $hotKey
     */
    public static function registerHotKey(AbstractHotKey $hotKey) {
        self::$hotkeys[] = $hotKey;
    }

    public static function unregisterAll() {
        self::$hotkeys = [];
    }
}
