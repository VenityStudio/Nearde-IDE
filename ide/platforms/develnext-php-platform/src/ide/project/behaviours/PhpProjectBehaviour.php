<?php
namespace ide\project\behaviours;

use develnext\lexer\inspector\PHPInspector;
use ide\formats\PhpCodeFormat;
use ide\project\AbstractProjectBehaviour;
use ide\project\behaviours\php\TreeCreatePhpClassMenuCommand;
use ide\project\behaviours\php\TreeCreatePhpFileMenuCommand;

/**
 * Class PhpProjectBehaviour
 * @package ide\project\behaviours
 */
class PhpProjectBehaviour extends AbstractProjectBehaviour
{
    /**
     * @var PHPInspector
     */
    protected $inspector;

    /**
     * @return int
     */
    public function getPriority()
    {
        return self::PRIORITY_CORE;
    }

    /**
     * @return PHPInspector
     */
    public function getInspector()
    {
        return $this->inspector;
    }

    /**
     * @throws \php\lang\IllegalArgumentException
     */
    public function inject()
    {
        $this->project->registerFormat(new PhpCodeFormat());

        $menu = $this->project->getTree()->getContextMenu();
        $menu->add(new TreeCreatePhpFileMenuCommand($this->project->getTree()), 'new');
        $menu->add(new TreeCreatePhpClassMenuCommand($this->project->getTree()), 'new');
    }
}