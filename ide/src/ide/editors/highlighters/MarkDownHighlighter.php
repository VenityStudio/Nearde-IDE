<?php

namespace ide\editors\highlighters;


use ide\editors\support\AbstractHighlighter;
use php\lib\str;
use php\util\Regex;

class MarkDownHighlighter extends AbstractHighlighter
{
    /**
     * @var array
     */
    private $classes = [
        "COMMENT" => "comment",
        "BOLD" => "string",
        "ITALIC" => "string",
        "MONOSPACE" => "string",
        "H1" => "string",
        "H2" => "string",
        "HMORE" => "string",
    ];

    /**
     * @throws \php\util\RegexException
     */
    public function applyHighlight(): void
    {
        $this->codeArea->getRichArea()->clearStyle(0, str::length($this->codeArea->getRichArea()->text));

        $regex = Regex::of(str::join([
            "(?<H1>^# (.)+$)",
            "|(?<H2>^#{2,2} (.)+$)",
            "|(?<HMORE>^#{3,6} (.)+$)",
            "|(?<BOLD>\\*{2,3}(.)+\\*{2,3})",
            "|(?<ITALIC>\\_(.)+\\_)",
            "|(?<MONOSPACE>`[^`]*`)",
            "|(?<COMMENT>^\\> (.)+$)",
        ], null),Regex::MULTILINE, $this->codeArea->getRichArea()->text);

        while ($regex->find())
        {
            $regex->group("H1") != null ? $group = "H1" :
                $regex->group("H2") != null ? $group = "H2" :
                    $regex->group("BOLD") != null ? $group = "BOLD" :
                        $regex->group("ITALIC") != null ? $group = "ITALIC" :
                            $regex->group("MONOSPACE") != null ? $group = "MONOSPACE" :
                                $regex->group("COMMENT") != null ? $group = "COMMENT" :
                                    $regex->group("HMORE") != null ? $group = "HMORE" : null;

            $this->codeArea->getRichArea()->setStyleClass($regex->start($group), $regex->end($group), $this->classes[$group]);
        }
    }
}