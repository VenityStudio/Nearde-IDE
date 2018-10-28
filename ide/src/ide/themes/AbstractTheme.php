<?php

namespace ide\themes;


abstract class AbstractTheme
{
    /**
     * @return string
     */
    abstract public function getName() : string;

    /**
     * @return array
     */
    abstract public function getCssFiles() : array;

    abstract public function getCodeEditorCssFile() : string;
}