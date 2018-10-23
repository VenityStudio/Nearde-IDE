<?php

namespace ide\formats;


use ide\editors\AbstractEditor;
use ide\editors\highlighters\GroovyHighlighter;
use ide\editors\TextEditor;
use php\lib\arr;
use php\lib\fs;

class GroovyFormat extends AbstractFormat
{

    /**
     * @param $file
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new TextEditor($file);
        $editor->getEditor()->setHighlighter(GroovyHighlighter::class);

        return $editor;
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return arr::has(["groovy", "gradle"], fs::ext($file));
    }

    public function getIcon()
    {
        return "icons/groovy16.png";
    }

    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {
        // TODO: Implement register() method.
    }
}