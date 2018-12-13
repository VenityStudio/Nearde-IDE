<?php
namespace ide\formats;

use ide\autocomplete\php\PhpAutoComplete;
use ide\autocomplete\ui\AutoCompletePane;
use ide\editors\AbstractEditor;
use ide\editors\TextEditor;
use ide\highlighters\PhpHighlighter;
use ide\Ide;
use php\lib\arr;
use php\lib\fs;

/**
 * Class PhpCodeFormat
 * @package ide\formats
 */
class PhpCodeFormat extends AbstractFormat
{
    /**
     * @var AutoCompletePane
     */
    private $autoComplete;

    /**
     * @param $file
     *
     * @param array $options
     * @return AbstractEditor
     * @throws \Exception
     */
    public function createEditor($file, array $options = [])
    {
        $editor = new TextEditor($file);
        $editor->getEditor()->setHighlighter(PhpHighlighter::class);

        $this->autoComplete = new AutoCompletePane($editor->getEditor()->getRichArea(),
            new PhpAutoComplete($options["inspector"] ?: Ide::project()->getInspector("php")));

        return $editor;
    }

    public function getIcon()
    {
        return 'icons/phpFile16.png';
    }

    /**
     * @param $file
     *
     * @return bool
     */
    public function isValid($file)
    {
        return arr::has(['php', 'phpt'], fs::ext($file));
    }

    /**
     * @param $any
     *
     * @return mixed
     */
    public function register($any)
    {
    }

    /**
     * @return AutoCompletePane
     */
    public function getAutoComplete(): AutoCompletePane
    {
        return $this->autoComplete;
    }
}