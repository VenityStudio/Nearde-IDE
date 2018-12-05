<?php
namespace ide;

use ide\formats\PhpCodeFormat;
use ide\project\supports\jppm\JPPMAppPluginSupport;
use ide\project\supports\JPPMProjectSupport;
use ide\project\supports\PHPProjectSupport;
use ide\project\templates\PhpProjectTemplate;

/**
 * Class PhpExtension
 * @package ide
 */
class PhpExtension extends AbstractExtension
{
    /**
     * @throws IdeException
     * @throws \Exception
     */
    public function onRegister()
    {
        Ide::get()->registerProjectSupport(PHPProjectSupport::class);
        Ide::get()->registerProjectSupport(JPPMProjectSupport::class);
        Ide::get()->registerProjectSupport(JPPMAppPluginSupport::class);

        Ide::get()->registerFormat(new PhpCodeFormat());
        Ide::get()->registerProjectTemplate(new PhpProjectTemplate());
    }

    public function onIdeStart()
    {
    }

    public function onIdeShutdown()
    {
    }

    public function getName(): string
    {
        return "PHP";
    }

    public function getAuthor(): string
    {
        return "jPHP Group & Venity Group";
    }

    public function getVersion(): string
    {
        return "17.0.0";
    }

    public function getIcon32(): string
    {
        return "icons/phpProject32.png";
    }
}