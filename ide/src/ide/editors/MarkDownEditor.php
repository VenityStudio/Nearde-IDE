<?php

namespace ide\editors;


use ide\editors\support\LineNumber;
use ide\Ide;
use ide\utils\FileUtils;
use markdown\Markdown;
use php\gui\designer\UXTextCodeArea;
use php\gui\UXInlineCssTextArea;
use php\gui\UXLabel;
use php\gui\UXNode;
use php\gui\UXSplitPane;
use php\gui\UXStyleClassedTextArea;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXVirtualizedScrollPane;
use php\gui\UXWebView;
use php\io\Stream;

class MarkDownEditor extends AbstractEditor
{
    /**
     * @var UXStyleClassedTextArea
     */
    private $editor;

    /**
     * @var UXWebView
     */
    private $browser;

    public function __construct(string $file)
    {
        parent::__construct($file);

        $this->editor = new UXStyleClassedTextArea();
        $this->browser = new UXWebView();

        $this->editor->appendText(Stream::getContents($this->file));
        $this->editor->on("keyUp", [$this, "render"]);
        $this->render();

        $this->editor->classes->add("syntax-text-area");
        $this->editor->stylesheets->add(FileUtils::urlPath(CodeEditor::getHighlightFile("php", "PhpStorm")));
        $this->editor->graphicFactory(new LineNumber());
    }

    public function getIcon()
    {
        return "icons/idea16.png";
    }

    public function load()
    {
        $this->render();
    }

    public function save()
    {
        Stream::putContents($this->file, $this->editor->text);
    }

    /**
     * @return UXNode
     */
    public function makeUi()
    {
        $tabpane = new UXTabPane();
        $tabpane->side = "LEFT";
        $tabpane->tabs->add($editor = new UXTab());
        $tabpane->tabs->add($view = new UXTab());

        $editor->text = "Редактор";
        $editor->graphic = Ide::getImage("icons/idea16.png");
        $editor->content = new UXVirtualizedScrollPane($this->editor);

        $view->text = "Просмотор";
        $view->graphic = Ide::getImage("icons/webBrowser16.png");
        $view->content = $this->browser;

        return $tabpane;
    }

    private function render() {
        $md = new Markdown();
        $content  = "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
        $content .= "<style>".Stream::getContents("res://.data/vendor/markdown.css")."</style>";
        $content .= "<article class=\"markdown-body\">";
        $content .= $md->render($this->editor->text);
        $content .= "</article>";
        $this->browser->engine->loadContent($content);
    }
}