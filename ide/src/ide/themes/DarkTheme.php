<?php

namespace ide\themes;


use ide\editors\CodeEditor;
use ide\Ide;
use ide\utils\FileUtils;

class DarkTheme extends AbstractTheme
{
    private $colors = [
        "#333333" => "#ffffff",
        "blue"    => "#5280c9"
    ];

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

    public function colorAlias(string $color): string
    {
        return $this->colors[$color] ?? $color;
    }
}