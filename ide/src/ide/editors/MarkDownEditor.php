<?php

namespace ide\editors;


use ide\editors\highlighters\MarkDownHighlighter;
use ide\editors\support\CodeArea;
use ide\Ide;
use ide\utils\FileUtils;
use markdown\Markdown;
use php\gui\UXNode;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXWebView;
use php\io\Stream;

class MarkDownEditor extends AbstractEditor
{
    /**
     * @var CodeArea
     */
    private $editor;

    /**
     * @var UXWebView
     */
    private $browser;

    public function __construct(string $file)
    {
        parent::__construct($file);

        $this->editor = new CodeArea();
        $this->browser = new UXWebView();

        $this->editor->setHighlighter(MarkDownHighlighter::class);
        $this->editor->getHighlighter()->on("applyHighlight", [$this, "render"]);
        $this->editor->getRichArea()->appendText(Stream::getContents($this->file));

        $this->editor->addStylesheet(CodeEditor::getHighlightFile("php", "PhpStorm"));
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
        Stream::putContents($this->file, $this->editor->getRichArea()->text);
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
        $editor->content = $this->editor;

        $view->text = "Просмотор";
        $view->graphic = Ide::getImage("icons/webBrowser16.png");
        $view->content = $this->browser;

        return $tabpane;
    }

    public function render() {
        $md = new Markdown();
        $content  = "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
        $content .= "<style>". Stream::getContents("res://.data/vendor/markdown.css") ."</style>";
        $content .= "<article class=\"markdown-body\">";
        $content .= $md->render($this->editor->getRichArea()->text);
        $content .= "</article>";
        $content .= "<script language='JavaScript'>". Stream::getContents("res://.data/vendor/prism.js") ."</script>";
        $this->browser->engine->loadContent($content);
    }
}