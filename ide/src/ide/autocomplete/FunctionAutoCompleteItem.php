<?php
namespace ide\autocomplete;

/**
 * Class FunctionAutoCompleteItem
 * @package ide\autocomplete
 */
class FunctionAutoCompleteItem extends AutoCompleteItem
{
    public function getDefaultIcon()
    {
        return "icons/function.png";
    }

    public function getIcon()
    {
        return $this->getDefaultIcon();
    }
}