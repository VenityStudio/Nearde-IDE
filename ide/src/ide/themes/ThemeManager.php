<?php

namespace ide\themes;


use ide\Logger;

class ThemeManager
{
    /**
     * @var AbstractTheme[]
     */
    private $themes;

    /**
     * @var string
     */
    private $default;

    /**
     * @param AbstractTheme $theme
     */
    public function register(AbstractTheme $theme) {
        if ($this->themes[$theme->getName()]) {
            Logger::error("Theme {$theme->getName()} exists");
            return;
        }

        $this->themes[$theme->getName()] = $theme;
    }

    public function setDefault(string $name) {
        if ($this->themes[$name]) {
            $this->default = $name;
            return;
        }

        Logger::error("Theme {$name} don`t exists");
    }

    public function get(string $name) : ?AbstractTheme {
        return $this->themes[$name];
    }

    public function getDefault(): ?AbstractTheme
    {
        return $this->get($this->default);
    }
}