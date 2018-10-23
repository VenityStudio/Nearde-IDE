<?php

namespace ide\editors\highlighters;

use ide\editors\support\AbstractHighlighter;
use php\lib\str;
use php\util\Regex;

class GroovyHighlighter extends AbstractHighlighter
{
    private $classes = [
        "STRING" => "string",
        "STRINGALT" => "string",
        "COMMENT" => "comment",
        "NUM" => "number",
        "KEYWORD" => "keyword"
    ];

    private $keywords = [
        "abstract", "as", "assert", "boolean", "break", "byte", "case", "catch", "char", "class", "const",
        "continue", "def", "default", "do", "double", "else", "enum", "extends", "false", "final", "finally",
        "float", "for", "goto", "if", "implements", "import", "in", "instanceof", "int", "interface",
        "long", "native", "new", "null", "package", "private", "protected", "public", "return", "short",
        "static", "strictfp", "super", "switch", "synchronized", "this", "threadsafe", "throw", "throws",
        "transient", "true", "try", "void", "volatile", "while", "task"
    ];
    
    public function applyHighlight() : void
    {
        $this->codeArea->getRichArea()->clearStyle(0, str::length($this->codeArea->getRichArea()->text));

        $regex = Regex::of(str::join([
            "(?<STRING>\"(.)+\")",
            "|(?<STRINGALT>\'(.)+\')",
            "|(?<COMMENT>//(.)+$)",
            "|(?<KEYWORD>\\b(" .str::join($this->keywords, "|"). ")\\b)",
            "|(?<NUM>([0-9])+)",
        ], null),Regex::MULTILINE, $this->codeArea->getRichArea()->text);

        while ($regex->find())
        {
            $regex->group("STRING") != null ? $group = "STRING" : 
            $regex->group("STRINGALT") != null ? $group = "STRINGALT" : 
            $regex->group("KEYWORD") != null ? $group = "KEYWORD" :
            $regex->group("NUM") != null ? $group = "NUM" :
            $regex->group("COMMENT") != null ? $group = "COMMENT" : null;

            $this->codeArea->getRichArea()->setStyleClass($regex->start($group), $regex->end($group), $this->classes[$group]);
        }
    }
}