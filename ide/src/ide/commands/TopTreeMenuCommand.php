<?php

namespace ide\commands;


use ide\editors\AbstractEditor;
use ide\Ide;
use ide\project\Project;
use ide\systems\FileSystem;
use php\gui\layout\UXHBox;
use php\gui\layout\UXVBox;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXTabPane;
use php\io\File;
use php\lib\str;

class TopTreeMenuCommand extends AbstractProjectCommand
{
    /**
     * @var UXHBox
     */
    private $box;

    /**
     * TopTreeMenuCommand constructor.
     */
    public function __construct()
    {
        $this->box = new UXHBox();
        $this->box->spacing = 8;

        Ide::get()->bind("openTab", function () {
            $this->update(Ide::project());
        });
    }

    public function makeMenuItem()
    {
        return false;
    }

    public function getName()
    {
        return "top project tree";
    }

    public function isAlways()
    {
        return true;
    }

    public function makeUiForHead()
    {
        $this->update();

        return $this->box;
    }

    public function update(?Project $project = null) {
        if (!$this->box->children->isEmpty()) $this->box->children->clear();

        try {
            if (!$project) return;
            if (!(($editor = Ide::get()->getMainForm()->{"fileTabPane"}->selectedTab->userData) instanceof AbstractEditor)) return;
        } catch (\Throwable $exception) { return; }

        /** @var $editor AbstractEditor */
        $path = $project->getName() . str::sub($editor->getFile(), str::length($project->getRootDir()));

        $arr = explode(File::DIRECTORY_SEPARATOR, $path);

        foreach ($arr as $key => $path) {
            $this->box->children->add($box = new UXHBox([
                $icon = new UXImageView(), new UXLabel($path)
            ]));

            if ($key == count($arr) - 1)
                $icon->image = Ide::getImage($editor->getIcon())->image;
            else $icon->image = Ide::getImage("icons/dirFile16.png")->image;

            if ($key != count($arr) - 1) {
                $box->add($up = Ide::getImage("icons/up16.png"));
                $up->rotate = 90;
            }

            $box->spacing = 8;
            $box->alignment = "CENTER";
        }
    }

    public function onExecute($e = null, AbstractEditor $editor = null)
    {
        // nup.
    }
}