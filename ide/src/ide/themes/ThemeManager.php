<?php

namespace ide\themes;


use ide\Ide;
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

        if ($this->default == null)
            $this->default = Ide::get()->getUserConfigValue("ide.theme", $theme->getName());
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

    /**
     * @return AbstractTheme[]
     */
    public function getAll() : array {
        return $this->themes;
    }
}