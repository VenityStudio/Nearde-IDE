<?php
namespace ide\autocomplete;

/**
 * Class ConstantAutoCompleteItem
 * @package ide\autocomplete
 */
class ConstantAutoCompleteItem extends AutoCompleteItem
{
    public function getIcon()
    {
        return $this->icon ?: 'icons/const16.png';
    }
}