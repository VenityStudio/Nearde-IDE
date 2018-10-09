<?php

namespace ide\editors\highlighters;


use ide\editors\support\AbstractHighlighter;
use php\lib\str;
use php\util\Regex;

class JsonHighlighter extends AbstractHighlighter
{
    private $classes = [
        "STRING" => "string",
        "STRINGALT" => "string",
        "COMMENT" => "comment",
    ];
    
    public function applyHighlight() : void
    {
        $this->codeArea->getRichArea()->clearStyle(0, str::length($this->codeArea->getRichArea()->text));

        $regex = Regex::of(str::join([
            "(?<STRING>\"(.)+\")",
            "|(?<STRINGALT>\'(.)+\')",
            "|(?<COMMENT>//(.)+$)",
        ], null),Regex::MULTILINE, $this->codeArea->getRichArea()->text);

        while ($regex->find())
        {
            $regex->group("STRING") != null ? $group = "STRING" : 
            $regex->group("STRINGALT") != null ? $group = "STRINGALT" : 
            $regex->group("COMMENT") != null ? $group = "COMMENT" : null;

            $this->codeArea->getRichArea()->setStyleClass($regex->start($group), $regex->end($group), $this->classes[$group]);
        }
    }
}