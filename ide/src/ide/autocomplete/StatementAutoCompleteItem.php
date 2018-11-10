<?php
namespace ide\autocomplete;


class StatementAutoCompleteItem extends AutoCompleteItem
{
    public function __construct($name, $description = '', $insert = null, $icon = null, $style = null)
    {
        parent::__construct($name, $description, $insert, $icon, $style);
    }

    public function getDefaultIcon()
    {
        return 'icons/type16.png';
    }
}