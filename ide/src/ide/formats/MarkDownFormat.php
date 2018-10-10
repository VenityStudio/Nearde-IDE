<?php
/**
 * Created by PhpStorm.
 * User: mwguy
 * Date: 05.10.18
 * Time: 23:09
 */

namespace ide\formats;


use ide\editors\AbstractEditor;
use ide\editors\MarkDownEditor;
use php\lib\fs;

class MarkDownFormat extends AbstractFormat
{

    /**
     * @param $file
     * @param array $options
     * @return AbstractEditor
     */
    public function createEditor($file, array $options = [])
    {
        return new MarkDownEditor($file);
    }

    /**
     * @param $file
     * @return bool
     */
    public function isValid($file)
    {
        return fs::ext($file) == "md";
    }

    /**
     * @param $any
     * @return mixed
     */
    public function register($any)
    {

    }

    public function getIcon()
    {
        return "icons/idea16.png";
    }
}