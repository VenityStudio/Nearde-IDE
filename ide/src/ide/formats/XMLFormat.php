<?php

namespace ide\formats;


use ide\editors\AbstractEditor;
use ide\editors\highlighters\XMLHighlighter;
use ide\editors\TextEditor;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;

class XMLFormat extends AbstractFormat
{

    /**
     * @param $file
     * @param array $options
     * @return AbstractEditor
     * @throws \Exception
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new TextEditor($file);
        $editor->getEditor()->setHighlighter(XMLHighlighter::class);

        return $editor;
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return arr::has([ "xml", "html", "xhtml" ], str::lower(fs::ext($file)));
    }

    public function getIcon()
    {
        return "icons/idea16.png";
    }

    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {
        return;
    }
}