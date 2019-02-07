<?php

namespace ide\editors;


class TokenType
{
    const SPACE    = 0;
    const NUMBER   = 1;
    const STRING   = 2;
    const OPERATOR = 3;
    const COMMENT  = 4;
    const ERROR    = 5;
    const TEXT     = 6;
    const VARIABLE = 7;
    const KEYWORD  = 8;
    const WARNING  = 9;
}