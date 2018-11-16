<?php

namespace ide\settings;


class SettingsContainer
{
    /**
     * @var AbstractSettings[]
     */
    private $settings;

    /**
     * @param AbstractSettings $settings
     */
    public function register(AbstractSettings $settings) {
        $this->settings[$settings->getName()] = $settings;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name) : bool {
        return isset($this->settings[$name]);
    }

    /**
     * @return AbstractSettings[]
     */
    public function getAll() : array {
        return $this->settings;
    }
}