<?php

namespace ide\highlighters;


use ide\editors\support\AbstractHighlighter;
use php\lib\str;
use php\util\Regex;

class PhpHighlighter extends AbstractHighlighter
{
    private $classes = [
        "STRING"    => "string",
        "STRINGALT" => "string",
        "COMMENT"   => "comment",
        "VAR"       => "variable",
        "NUM"       => "number",
        "KEYWORD"       => "keyword",
    ];

    private $keyWords = [
        '__halt_compiler', 'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch',
        'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif', 'empty',
        'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends', 'final',
        'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include', 'include_once', 'instanceof',
        'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or', 'print', 'private', 'protected', 'public',
        'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var', 'while',
        'xor', 'self', 'fn'
    ];

    /**
     * @throws \php\util\RegexException
     */
    public function applyHighlight(): void
    {
        $this->codeArea->getRichArea()->clearStyle(0, str::length($this->codeArea->getRichArea()->text));

        $regex = Regex::of(str::join([
            "(?<STRING>\"(.)+\")",
            "|(?<STRINGALT>\'(.)+\')",
            "|(?<COMMENT>//[^\\n]*|/\\*(.|\\R)*?\\*/)",
            "|(?<KEYWORD>\\b(". str::join($this->keyWords, "|") .")\\b)",
            "|(?<VAR>\\$[a-zA-Z_$][a-zA-Z_$0-9]*)",
            "|(?<NUM>([0-9])+)",
        ], null),Regex::MULTILINE, $this->codeArea->getRichArea()->text);

        while ($regex->find())
        {
            $regex->group("STRING") != null ? $group = "STRING" :
                $regex->group("STRINGALT") != null ? $group = "STRINGALT" :
                        $regex->group("KEYWORD") != null ? $group = "KEYWORD" :
                            $regex->group("NUM") != null ? $group = "NUM" :
                                    $regex->group("COMMENT") != null ? $group = "COMMENT" :
                                        $regex->group("VAR") != null ? $group = "VAR" : null;

            $this->codeArea->getRichArea()->setStyleClass($regex->start($group), $regex->end($group), $this->classes[$group]);
        }
    }
}