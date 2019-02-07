<?php

namespace ide\editors\highlighters;


use ide\editors\support\AbstractHighlighter;
use ide\editors\Token;
use ide\editors\TokenType;

abstract class AbstractTokenHighlighter extends AbstractHighlighter
{
    /**
     * @var string
     */
    protected $text;

    public function applyHighlight(): void
    {
        $this->text = $this->codeArea->getRichArea()->text;
        $this->clearCodeAreaStyle();
        $tokens = $this->tokenize();

        $currentPosition = 0;

        foreach ($tokens as $token) {
            $classes = [];

            foreach ($token->getTypes() as $type)
                switch ($type) {
                    case TokenType::NUMBER; $classes[] = "number"; break;
                    case TokenType::STRING; $classes[] = "string"; break;
                    case TokenType::OPERATOR; $classes[] = "operator"; break;
                    case TokenType::COMMENT; $classes[] = "comment"; break;
                    case TokenType::ERROR; $classes[] = "error"; break;
                    case TokenType::TEXT; $classes[] = "text"; break;
                    case TokenType::VARIABLE; $classes[] = "variable"; break;
                    case TokenType::KEYWORD; $classes[] = "keyword"; break;
                    case TokenType::WARNING; $classes[] = "warning"; break;

                    case TokenType::SPACE;
                    default:
                        $classes[] = "";
                }

            foreach ($classes as $class)
                if ($class)
                    $this->codeArea->getRichArea()->setStyleClass(
                        $currentPosition, $currentPosition + $token->getSize(), $class);

            $currentPosition += $token->getSize();
        }
    }

    /**
     * @return Token[]
     */
    abstract public function tokenize(): array;
}