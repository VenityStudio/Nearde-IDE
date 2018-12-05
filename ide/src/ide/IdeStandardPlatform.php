<?php

namespace ide;

use ide\themes\DarkTheme;

class IdeStandardPlatform extends AbstractPlatform
{
    /**
     * @return string
     */
    public function getIDEName(): string
    {
        return "Nearde IDE";
    }

    /**
     * @return string
     */
    public function getIDEVersion(): string
    {
        return "RC 2";
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getIDEIcon(): string
    {
        switch (Ide::get()->getThemeManager()->getDefault()->getName()) {
            case (new DarkTheme())->getName(): return "logos/dark.png"; break;
            default: return "logos/light.png";
        }
    }

    public function onIdeStart()
    {
        Ide::get()->registerExtension(new IdeStandardExtension());
    }

    public function onIdeShutdown()
    {

    }
}