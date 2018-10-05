<?php
namespace ide\commands;

use develnext\lexer\inspector\PHPInspector;
use ide\autocomplete\AutoComplete;
use ide\autocomplete\php\PhpAutoComplete;
use ide\editors\AbstractEditor;
use ide\editors\CodeEditor;
use ide\Ide;
use ide\misc\AbstractCommand;
use ide\misc\SimpleSingleCommand;
use ide\systems\IdeSystem;
use ide\ui\UXError;
use ide\utils\FileUtils;
use ide\utils\UiUtils;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXVBox;
use php\gui\text\UXFont;
use php\gui\UXForm;
use php\gui\UXTextArea;
use php\lang\Environment;
use php\lib\fs;

/**
 * @package ide\commands
 */
class IdeDebuggerCommand extends AbstractCommand
{
    /**
     * @var AbstractCommand
     */
    public $command;

    public function getName()
    {
        return 'IDE Debugger';
    }

    public function getCategory()
    {
        return 'help';
    }

    public function getIcon()
    {
        return "icons/star16.png";
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        if ($this->command) {
            if (IdeSystem::isDevelopment()) {
                $this->command->onExecute();
            }

            return;
        }

        $form = new UXForm();
        $form->icons->add((Ide::getImage($this->getIcon()))->image);
        $form->title = $this->getName();
        $form->addStylesheet("./");

        $ui = new UXVBox();
        $ui->spacing = 8;
        $ui->padding = 8;

        $inspector = new PHPInspector();
        $inspector->loadDirectory("res://.theme/style.css");

        $file = IdeSystem::getFile("debugger.php");
        $editor = new CodeEditor($file, 'php', [
            "autoComplete" => new PhpAutoComplete($inspector)
        ]);
        $editor->setEmbedded(true);
        $editor->setSourceFile(false);
        $editor->registerDefaultCommands();
        $editor->loadContentToArea();

        if (!$editor->getValue()) {
            $editor->setValue("<?\nuse \\ide\\Ide;\n");
        }

        $editor->on('update', function () use ($editor) {
            $editor->save();
        });

        $pane = UiUtils::makeCommandPane([
            $this->command = SimpleSingleCommand::makeWithText('Запустить', 'icons/run16.png', function () use ($editor, $file) {
                try {
                    include IdeSystem::getFile("debugger.php")->getCanonicalPath();
                } catch (\Throwable $throwable) {
                    (new UXError($throwable))->show();
                }
            }),
            SimpleSingleCommand::makeWithText('Скрыть', 'icons/square16.png', function () use ($editor, $form) {
                $form->hide();
                $editor->lockHandles();
                $this->command = null;
            })
        ]);
        $pane->spacing = 5;
        $pane->minHeight = 25;

        $textArea = $editor->makeUi();
        UXVBox::setVgrow($textArea, 'ALWAYS');

        $ui->add($textArea);
        $ui->add($pane);

        UXAnchorPane::setAnchor($ui, 0);
        $form->add($ui);
        $form->show();
    }

    public function isAlways()
    {
        return true;
    }

    public function getAccelerator()
    {
        return 'F11';
    }
}