<?php
namespace ide\autocomplete;

/**
 * Class PropertyAutoCompleteItem
 * @package ide\autocomplete
 */
class PropertyAutoCompleteItem extends AutoCompleteItem
{
    public function getDefaultIcon()
    {
        return 'icons/field16.png';
    }

    public function getIcon()
    {
        return $this->getDefaultIcon();
    }
}