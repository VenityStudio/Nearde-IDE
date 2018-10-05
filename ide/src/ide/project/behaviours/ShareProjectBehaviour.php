<?php
namespace ide\project\behaviours;

use ide\account\api\ProjectArchiveService;
use ide\account\api\ProjectService;
use ide\account\api\ServiceResponse;
use ide\account\ui\NeedAuthPane;
use ide\forms\area\ShareProjectArea;
use ide\forms\MessageBoxForm;
use ide\Ide;
use ide\project\AbstractProjectBehaviour;
use ide\project\control\CommonProjectControlPane;
use ide\ui\Notifications;
use ide\utils\TimeUtils;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXHyperlink;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXParent;
use php\net\URL;
use php\time\Time;

class ShareProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * ...
     */
    public function inject()
    {
        // noup
    }

    /**
     * see PRIORITY_* constants
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_COMPONENT;
    }
}