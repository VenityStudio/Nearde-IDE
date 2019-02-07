<?php

namespace ide\formats;

use ide\editors\AbstractEditor;
use ide\editors\highlighters\YamlHighlighter;
use ide\editors\TextEditor;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;

class YamlFormat extends AbstractFormat
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
        $editor->getEditor()->setHighlighter(YamlHighlighter::class);

        return $editor;
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return arr::has([ "yml", "yaml" ], str::lower(fs::ext($file)));
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