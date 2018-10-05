<?php

namespace ide\editors;


use ide\Ide;
use markdown\Markdown;
use php\gui\designer\UXTextCodeArea;
use php\gui\UXNode;
use php\gui\UXSplitPane;
use php\gui\UXWebView;
use php\io\Stream;

class MarkDownEditor extends AbstractEditor
{
    /**
     * @var UXTextCodeArea
     */
    private $editor;

    /**
     * @var UXWebView
     */
    private $browser;

    public function __construct(string $file)
    {
        parent::__construct($file);

        $this->editor = new UXTextCodeArea();
        $this->browser = new UXWebView();

        $this->editor->text = Stream::getContents($this->file);
        $this->editor->on("keyUp", [$this, "render"]);
        $this->render();
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
        return new UXSplitPane([
            $this->editor, $this->browser
        ]);
    }

    private function render() {
        $md = new Markdown();
        $content  = "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
        $content .= "<style>".Stream::getContents("res://.data/vendor/markdown.css")."</style>";
        $content .= "<article class=\"markdown-body\">";
        $content .= $md->render(Stream::getContents($this->file));
        $content .= "</article>";
        $this->browser->engine->loadContent($content);
    }
}