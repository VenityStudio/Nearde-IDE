<?php

namespace ide\editors\highlighters;


use ide\editors\support\AbstractHighlighter;
use php\lib\str;
use php\util\Regex;

class YamlHighlighter extends AbstractHighlighter
{
    private $classes = [
        "STRING" => "string",
        "STRINGALT" => "string",
        "COMMENT" => "comment",
        "NUMBER" => "number",
        "KEYWORD" => "keyword",
    ];
    
    public function applyHighlight() : void
    {
        $this->clearCodeAreaStyle();

        $regex = Regex::of(str::join([
            "(?<NUMBER>[-+]?[0-9]*\.?[0-9]+)",
            "|(?<STRING>\"(.)+\")",
            "|(?<STRINGALT>\'(.)+\')",
            "|(?<COMMENT>#(.)+$)",
            "|(?<KEYWORD>([A-Za-z0-9_-]+)\:)",
        ], null),Regex::MULTILINE, $this->codeArea->getRichArea()->text);

        while ($regex->find())
        {
            $regex->group("NUMBER") != null ? $group = "NUMBER" :
            $regex->group("STRING") != null ? $group = "STRING" :
            $regex->group("STRINGALT") != null ? $group = "STRINGALT" :
            $regex->group("COMMENT") != null ? $group = "COMMENT" : null;
            $regex->group("KEYWORD") != null ? $group = "KEYWORD" : null;

            $this->codeArea->getRichArea()->setStyleClass($regex->start($group), $regex->end($group), $this->classes[$group]);
        }
    }
}