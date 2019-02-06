<?php

use ide\Ide;
use ide\IdeClassLoader;
use ide\l10n\LocalizedString;
use ide\Logger;
use ide\systems\IdeSystem;
use php\gui\designer\UXDesignProperties;
use php\gui\UXDialog;
use php\gui\UXNode;

$loader = new IdeClassLoader(false, IdeSystem::getOwnLibVersion());
$loader->register(true);

IdeSystem::setLoader($loader);

if (!IdeSystem::isDevelopment()) {
    Logger::setLevel(Logger::LEVEL_INFO);
}

$app = new Ide();
$app->addStyle('/.theme/style.css');
$app->launch();

/**
 * @param $code
 * @param array ...$args
 * @return UXNode|string
 */
function _($code, ...$args) {
    $ideLocalizer = Ide::get()->getLocalizer();

    if (!$ideLocalizer->language) {
        return $code;
    }

    if ($code instanceof UXNode) {
        $ideLocalizer->translateNode($code, ...$args);
        return $code;
    } else if ($code instanceof \php\gui\UXTooltip) {
        $ideLocalizer->translateTooltip($code, ...$args);
        return $code;
    } else if ($code instanceof \php\gui\UXMenuItem) {
        $ideLocalizer->translateMenuItem($code, ...$args);
        return $code;
    } else if ($code instanceof \php\gui\UXMenu) {
        $ideLocalizer->translateMenu($code, ...$args);
        return $code;
    } else if ($code instanceof \php\gui\UXTab) {
        $ideLocalizer->translateTab($code, ...$args);

        return $code;
    } else if ($code instanceof UXDesignProperties) {
        return $ideLocalizer->translateDesignProperties($code);
    } else {
        $result = $ideLocalizer->translate($code, (array)$args);

        if (!($result instanceof LocalizedString)) {
            throw new Exception("$code result is not localized string");
        }

        return $result;
    }
}

function dump($arg)
{
    ob_start();

        var_dump($arg);
        $str = ob_get_contents();

    ob_end_clean();

    UXDialog::showAndWait($str);
}

/**
 * @param $name
 * @return \php\gui\UXImageView
 */
function ico($name)
{
    return Ide::get()->getImage("icons/$name.png");
}