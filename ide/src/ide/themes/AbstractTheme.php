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

    /**
     * @param string $color
     * @return string
     */
    public function colorAlias(string $color) : string {
        return $color;
    }

    /**
     * @param string $icon
     * @return string
     */
    public function iconAlias(string $icon) : string {
        return $icon;
    }
}