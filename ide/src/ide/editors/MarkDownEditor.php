<?php

namespace ide\editors;


use ide\editors\highlighters\MarkDownHighlighter;
use ide\editors\support\CodeArea;
use ide\editors\support\Gutter;
use ide\Ide;
use ide\utils\FileUtils;
use markdown\Markdown;
use php\gui\UXApplication;
use php\gui\UXNode;
use php\gui\UXTab;
use php\gui\UXTabPane;
use php\gui\UXWebView;
use php\io\Stream;

class MarkDownEditor extends AbstractEditor
{
    /**
     * @var TextEditor
     */
    private $editor;

    /**
     * @var UXWebView
     */
    private $browser;

    /**
     * MarkDownEditor constructor.
     * @param string $file
     * @throws \php\io\IOException
     */
    public function __construct(string $file)
    {
        parent::__construct($file);

        $this->editor = new TextEditor($file);
        $this->browser = new UXWebView();
        $this->editor->getEditor()->getHighlighter()->on("applyHighlight", [$this, "render"]);
    }

    public function getIcon()
    {
        return "icons/idea16.png";
    }

    public function load()
    {
        $this->editor->load();
        $this->render();
    }

    public function save()
    {
        $this->editor->save();
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

        $editor->text = _("ide.editor.markdown.editor");
        $editor->graphic = Ide::getImage("icons/idea16.png");
        $editor->content = $this->editor->getEditor();

        $view->text = _("ide.editor.markdown.view");
        $view->graphic = Ide::getImage("icons/webBrowser16.png");
        $view->content = $this->browser;

        $editor->closable = $view->closable = false;

        return $tabpane;
    }

    public function render() {
        Ide::async(function () {
            $md = new Markdown();
            $content  = "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
            $content .= "<style>". Stream::getContents("res://.data/vendor/markdown.css") ."</style>";
            $content .= "<article class=\"markdown-body\">";
            $content .= $md->render($this->editor->getEditor()->getRichArea()->text);
            $content .= "</article>";
            UXApplication::runLaterAndWait(function () use ($content) {
                $this->browser->engine->loadContent($content);
            });
        });
    }
}