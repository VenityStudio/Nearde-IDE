<?php
namespace ide\forms;

use ide\Ide;
use ide\Logger;
use ide\systems\SplashTipSystem;
use php\gui\effect\UXColorAdjustEffect;
use php\gui\effect\UXSepiaToneEffect;
use php\gui\event\UXEvent;
use php\gui\framework\AbstractForm;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXImage;
use php\gui\UXImageArea;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\io\IOException;
use php\io\Stream;
use php\lang\Thread;
use php\lang\ThreadPool;
use php\lib\str;
use php\time\Time;

/**
 * @property UXLabel $version
 * @property UXImageView $image
 * @property UXLabel $accountNameLabel
 * @property UXAnchorPane $accountAvatarImage
 * @property UXHBox $accountPane
 * @property UXLabel $tip
 * @property UXHBox $tipBox
 */
class SplashForm extends AbstractIdeForm
{
    protected function init()
    {
        Logger::debug("Init form ...");

        $this->centerOnScreen();
        $this->version->text = $this->_app->getVersion();
        $this->tip->text = SplashTipSystem::get(Ide::get()->getLanguage()->getCode());

        (new Thread(function () {
            while (true) {
                try {
                    if ($this->_app->getMainForm()->visible)
                        $this->hide();
                } catch (\Throwable $e) {
                    ;
                }

                sleep(1);
            }
        }))->start();
    }

    /**
     * @param UXEvent $e
     * @event tip.click
     */
    public function doTipClick(UXEvent $e)
    {
        $this->tip->text = SplashTipSystem::get(Ide::get()->getLanguage()->getCode());
        $e->consume();
    }
}