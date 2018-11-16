<?php

namespace ide\settings;


use ide\AbstractExtension;
use ide\Ide;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXNode;

class ExtensionsSettings extends AbstractSettings
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return "ide.settings.extensions";
    }

    /**
     * @return string
     */
    public function getIcon16(): string
    {
        return "icons/plugin16.png";
    }

    /**
     * @return UXNode
     * @throws \Exception
     */
    public function getNode(): UXNode
    {
        $list = new UXListView();
        UXAnchorPane::setAnchor($list, 8);
        $list->setCellFactory(function (UXListCell $cell, AbstractExtension $extension) {
            if ($extension) {
                $titleName = new UXLabel($extension->getName() . " " . $extension->getVersion());
                $titleName->style = '-fx-font-weight: bold;';

                if ($extension->isSystem())
                    $titleName->text .= " (system)";

                $titleDescription = new UXLabel($extension->getAuthor());
                $titleDescription->opacity = 0.7;

                $title  = new UXVBox([$titleName, $titleDescription]);
                $title->spacing = 0;

                $line = new UXHBox([Ide::getImage($extension->getIcon32(), [32, 32]), $title]);
                $line->spacing = 7;
                $line->padding = 5;
                $cell->text = null;
                $cell->graphic = $line;
            }
        });

        foreach (Ide::get()->getExtensions() as $extension) $list->items->add($extension);

        return $list;
    }

    public function save(): void
    {
        // noup
    }

    public function toDefault(): void
    {
        // noup
    }
}