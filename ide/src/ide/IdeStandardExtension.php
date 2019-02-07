<?php
namespace ide;

use ide\commands\CloseProjectCommand;
use ide\commands\ExitCommand;
use ide\commands\ExportProjectCommand;
use ide\commands\IdeLogShowCommand;
use ide\commands\NewProjectCommand;
use ide\commands\OpenProjectCommand;
use ide\commands\RunTaskCommand;
use ide\commands\SaveProjectCommand;
use ide\commands\SaveProjectForLibraryCommand;
use ide\commands\SettingsCommand;
use ide\commands\AboutCommand;
use ide\commands\TopTreeMenuCommand;
use ide\editors\hotkey\AutoBracketHotKey;
use ide\editors\hotkey\DuplicateHotKey;
use ide\editors\hotkey\EnterHotKey;
use ide\editors\TextEditor;
use ide\formats\CssCodeFormat;
use ide\formats\GroovyFormat;
use ide\formats\JsonFormat;
use ide\formats\MarkDownFormat;
use ide\formats\TextCodeFormat;
use ide\formats\WelcomeFormat;
use ide\formats\XMLFormat;
use ide\formats\YamlFormat;
use ide\systems\DialogSystem;

class IdeStandardExtension extends AbstractExtension
{
    /**
     * @throws \Exception
     */
    public function onRegister()
    {
        DialogSystem::registerDefaults();

        // commands
        Ide::get()->registerCommand(new NewProjectCommand());
        Ide::get()->registerCommand(new OpenProjectCommand());
        Ide::get()->registerCommand(new SaveProjectCommand());
        Ide::get()->registerCommand(new ExportProjectCommand());
        Ide::get()->registerCommand(new SaveProjectForLibraryCommand());
        Ide::get()->registerCommand(new CloseProjectCommand());
        Ide::get()->registerCommand(new ExitCommand());
        Ide::get()->registerCommand(new RunTaskCommand());
        Ide::get()->registerCommand(new IdeLogShowCommand());
        Ide::get()->registerCommand(new SettingsCommand());
        Ide::get()->registerCommand(new AboutCommand());
        Ide::get()->registerCommand(new TopTreeMenuCommand());

        // formats
        Ide::get()->registerFormat(new WelcomeFormat());
        Ide::get()->registerFormat(new CssCodeFormat());
        Ide::get()->registerFormat(new TextCodeFormat());
        Ide::get()->registerFormat(new MarkDownFormat());
        Ide::get()->registerFormat(new GroovyFormat());
        Ide::get()->registerFormat(new XMLFormat());
        Ide::get()->registerFormat(new YamlFormat());
        Ide::get()->registerFormat(new JsonFormat());

        TextEditor::registerHotKey(new DuplicateHotKey());
        //TextEditor::registerHotKey(new EnterHotKey()); // БАГНУТЫЙ
        TextEditor::registerHotKey(new AutoBracketHotKey());
    }

    public function onIdeStart()
    {

    }

    public function onIdeShutdown()
    {

    }

    public function getName(): string
    {
        return "IDE Platform";
    }

    public function getAuthor(): string
    {
        return "jPHP Group & Venity Group";
    }

    public function getVersion(): string
    {
        return Ide::get()->getVersion();
    }

    public function getIcon32(): string
    {
        return Ide::get()->getIcon();
    }

    public function isSystem(): bool
    {
        return true;
    }
}
