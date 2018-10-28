<?php

namespace ide\themes;


use ide\editors\CodeEditor;
use ide\utils\FileUtils;

class LightTheme extends AbstractTheme
{

    /**
     * @return string
     */
    public function getName(): string
    {
        return "light";
    }

    /**
     * @return array
     */
    public function getCssFiles(): array
    {
        return [
            "/.theme/style.css"
        ];
    }

    public function getCodeEditorCssFile(): string
    {
        return CodeEditor::getHighlight("PhpStorm")->getAbsolutePath();
    }
}