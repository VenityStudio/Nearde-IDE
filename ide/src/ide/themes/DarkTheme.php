<?php

namespace ide\themes;


use ide\editors\CodeEditor;
use ide\utils\FileUtils;

class DarkTheme extends AbstractTheme
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return "dark";
    }

    /**
     * @return array
     */
    public function getCssFiles(): array
    {
        return [
            "/.theme/style.css",
            "/.theme/dark.css"
        ];
    }

    public function getCodeEditorCssFile(): string
    {
        return CodeEditor::getHighlight("PhpStorm-Dracula")->getAbsolutePath();
    }
}