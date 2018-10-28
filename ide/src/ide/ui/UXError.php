<?php

namespace ide\ui;

use php\gui\layout\UXAnchorPane;
use php\gui\UXAlert;
use php\gui\UXTextArea;

class UXError extends UXAlert
{
    /**
     * UXError constructor.
     * @param \Throwable $throwable
     * @throws \Exception
     */
    public function __construct(\Throwable $throwable)
    {
        parent::__construct('ERROR');
        $this->title = _('error.title');
        $this->headerText = _('error.unknown.help.text');
        $this->contentText = $throwable->getMessage();
        $this->setButtonTypes([_('btn.exit.dn'), _('btn.resume')]);

        $class = get_class($throwable);

        $content = new UXTextArea("{$class}\n{$throwable->getMessage()}\n\n"
            . _("error.in.file", $throwable->getFile())
            . "\n\t-> " . _("error.at.line", $throwable->getLine()) . "\n\n" . $throwable->getTraceAsString());

        $content->padding = 10;
        UXAnchorPane::setAnchor($content, 0);
        $this->expandableContent = $content;
        $this->expanded = true;
    }

    /**
     * @throws \Exception
     */
    public function show()
    {
        switch (parent::showAndWait()) {
            case _('btn.exit.dn'):
                Ide::get()->shutdown();
                break;
        }
    }

    /**
     * @return mixed|void
     * @throws \Exception
     */
    public function showAndWait()
    {
        $this->show();
    }
}