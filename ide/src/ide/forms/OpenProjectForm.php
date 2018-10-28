<?php
namespace ide\forms;

use ide\account\api\ServiceResponse;
use ide\account\ui\NeedAuthPane;
use ide\forms\mixins\DialogFormMixin;
use ide\forms\mixins\SavableFormMixin;
use ide\Ide;
use ide\library\IdeLibraryProjectResource;
use ide\library\IdeLibraryResource;
use ide\Logger;
use ide\project\Project;
use ide\project\ProjectConfig;
use ide\systems\DialogSystem;
use ide\systems\FileSystem;
use ide\systems\ProjectSystem;
use ide\ui\FlowListViewDecorator;
use ide\ui\ImageBox;
use ide\ui\Notifications;
use ide\utils\FileUtils;
use ide\utils\UiUtils;
use php\gui\event\UXEvent;
use php\gui\event\UXMouseEvent;
use php\gui\framework\AbstractForm;
use php\gui\framework\Preloader;
use php\gui\layout\UXAnchorPane;
use php\gui\layout\UXHBox;
use php\gui\layout\UXScrollPane;
use php\gui\layout\UXVBox;
use php\gui\UXApplication;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXDirectoryChooser;
use php\gui\UXFileChooser;
use php\gui\UXForm;
use php\gui\UXHyperlink;
use php\gui\UXImageView;
use php\gui\UXLabel;
use php\gui\UXListCell;
use php\gui\UXListView;
use php\gui\UXTabPane;
use php\gui\UXTextField;
use php\io\File;
use php\lang\Thread;
use php\lib\arr;
use php\lib\fs;
use php\lib\Items;
use php\lib\Str;
use php\time\Time;

/**
 *
 * @property UXImageView $icon
 * @property UXScrollPane $projectList
 * @property UXTextField $pathField
 * @property UXButton $openButton
 * @property UXTabPane $tabPane
 * @property UXListView $libraryList
 * @property UXListView $sharedList
 * @property UXListView $embeddedLibraryList
 * @property UXAnchorPane $sharedPane
 * @property UXTextField $projectQueryField
 * @property UXButton $projectSearchButton
 *
 * Class OpenProjectForm
 * @package ide\forms
 */
class OpenProjectForm extends AbstractIdeForm
{
    use DialogFormMixin;
    use SavableFormMixin;

    /**
     * @var FlowListViewDecorator
     */
    protected $projectListHelper;

    protected $_sharedList;

    /**
     * @param null $tab
     */
    public function __construct($tab = null)
    {
        parent::__construct();
    }

    public function init()
    {
        parent::init();

        $this->projectListHelper = new FlowListViewDecorator($this->projectList->content);
        $this->projectListHelper->setEmptyListText(_('project.open.empty.list'));
        $this->projectListHelper->setMultipleSelection(true);
        $this->projectListHelper->on('remove', [$this, 'doRemove']);
        $this->projectListHelper->on('beforeRemove', function ($nodes) {
            $what = [];
            foreach ($nodes as $node) {
                $file = $node->data('file');

                if ($file && $file->exists())  {
                    $what[] = $node->data('name');
                }
            }

            if (!MessageBoxForm::confirmDelete($what, $this)) {
                return true;
            }

            return false;
        });

        $this->icon->image = Ide::get()->getImage('icons/open32.png')->image;
        $this->modality = 'APPLICATION_MODAL';
        $this->title = _('project.open.title');
    }

    public function update(string $searchText = '')
    {
        $searchText = str::lower($searchText);
        $emptyText = $this->projectListHelper->getEmptyListText();

        $this->projectListHelper->setEmptyListText('project.open.searching');
        $this->projectListHelper->clear();

        $th = new Thread(function () use ($emptyText, $searchText) {
            $projectDirectory = File::of(Ide::get()->getUserConfigValue('projectDirectory'));

            $projects = [];

            foreach ($projectDirectory->findFiles() as $file) {
                if ($file->isDirectory()) {
                    $project = arr::first($file->findFiles(function (File $directory, $name) {
                        return Str::endsWith($name, '.dnproject') || Str::endsWith($name, '.ndproject');
                    }));

                    if ($project) {
                        $projects[] = $project;
                    }
                }
            }

            arr::sort($projects, function (File $a, File $b) {
                if ($a->lastModified() === $b->lastModified()) {
                    return 0;
                }

                return $a->lastModified() > $b->lastModified() ? -1 : 1;
            });

            foreach ($projects as $project) {
                /** @var File $project */
                $config = ProjectConfig::createForFile($project);
                $template = $config->getTemplate();
                $name = str::lower(fs::nameNoExt($project->getName()));

                if ($searchText && !str::contains($name, $searchText)) {
                    continue;
                }

                uiLater(function () use ($project, $template) {
                    $one = new ImageBox(72, 48);
                    $one->data('file', $project);
                    $one->data('name', fs::pathNoExt($project->getName()));
                    $one->setTitle(fs::pathNoExt($project->getName()));
                    $one->setImage(Ide::get()->getImage($template ? $template->getIcon32() : 'icons/question32.png')->image);
                    $one->setTooltip(fs::nameNoExt($project->getName()));

                    $one->on('click', function (UXMouseEvent $e) {
                        $fix = $e;
                        UXApplication::runLater(function () use ($e) {
                            $this->doProjectListClick($e);
                        });
                    });

                    $this->projectListHelper->add($one);
                });
            }

            $this->projectListHelper->setEmptyListText($emptyText);

            uiLater(function () use ($projectDirectory, $projects) {
                if (!$projects) {
                    $this->projectListHelper->clear();
                }

                $this->pathField->text = $projectDirectory;
            });
        });

        $th->start();
    }

    /**
     * @event showing
     */
    public function doShowing()
    {
        $this->update($this->projectQueryField->text);
    }

    /**
     * @event openButton.action
     */
    public function doOpenButtonClick()
    {
        if ($file = DialogSystem::getOpenProject()->execute()) {
            $this->hide();

            UXApplication::runLater(function () use ($file) {
                if (Str::endsWith($file, ".zip")) {
                    ProjectSystem::import($file);
                } else {
                    ProjectSystem::open($file);
                }
            });
        }
    }

    /**
     * @param $nodes
     * @return bool
     */
    public function doRemove(array $nodes)
    {
        foreach ($nodes as $node) {
            $file = $node->data('file');

            if ($file && $file->exists()) {
                $directory = File::of($file)->getParent();

                if (Ide::project()
                    && FileUtils::normalizeName(Ide::project()->getRootDir()) == FileUtils::normalizeName($directory)) {
                    ProjectSystem::closeWithWelcome();
                }

                if (!FileUtils::deleteDirectory($directory)) {
                    Notifications::error('project.open.error.delete.title', 'project.open.error.delete.description');
                    $this->update($this->projectQueryField->text);
                }
            }
        }
    }

    /**
     * @event projectQueryField.keyUp
     * @event projectSearchButton.action
     */
    public function doSearchProject()
    {
        $this->update($this->projectQueryField->text);
    }

    /**
     * @param UXMouseEvent $e
     */
    public function doProjectListClick(UXMouseEvent $e)
    {
        if ($e->clickCount > 1) {
            $node = $this->projectListHelper->getSelectionNode();
            $file = $node ? $node->data('file') : null;

            if ($file && $file->exists()) {
                $this->showPreloader('project.open.wait');

                waitAsync(100, function () use ($file) {
                    try {
                        if (ProjectSystem::open($file)) {
                            $this->hide();
                        }
                    } finally {
                        $this->hidePreloader();
                    }
                });
            } else {
                UXDialog::show(_('project.open.error'), 'ERROR');
            }
        }
    }

    /**
     * @event pathButton.click
     */
    public function doChoosePath()
    {
        $path = DialogSystem::getProjectsDirectory()->execute();

        if ($path !== null) {
            $this->pathField->text = $path;

            Ide::get()->setUserConfigValue('projectDirectory', $path);
            $this->update($this->projectQueryField->text);
        }
    }

    /**
     * @event embeddedLibraryList.click-2x
     * @event libraryList.click-2x
     * @param UXEvent $e
     */
    public function doCreate(UXEvent $e)
    {
        /** @var UXListView $listView */
        $listView = $e->sender;

        /** @var IdeLibraryProjectResource $selected */
        $selected = $listView->selectedItem;

        if ($selected) {
            $path = File::of($this->pathField->text);

            if (!$path->isDirectory()) {
                if (!$path->mkdirs()) {
                    UXDialog::show(_('project.open.fail.create.directory'), 'ERROR');
                    return;
                }
            }

            $name = FileUtils::stripExtension(File::of($selected->getPath())->getName());

            $this->showPreloader('project.open.wait');

            waitAsync(100, function () use ($path, $name, $selected) {
                try {
                    ProjectSystem::import($selected->getPath(), "$path/$name", $name, [$this, 'hide']);
                    $this->hide();
                } finally {
                    $this->hidePreloader();
                }
            });
        }
    }

    public function doDelete(UXEvent $e)
    {
        /** @var UXListView $listView */
        $listView = $e->sender;

        /** @var IdeLibraryProjectResource $selected */
        $selected = $listView->selectedItem;

        if ($selected) {
            if (MessageBoxForm::confirmDelete($selected->getName(), $this)) {
                Ide::get()->getLibrary()->delete($selected);
                $this->updateLibrary();
                $listView->selectedIndex = -1;
            }
        }
    }

    public function selectLibraryResource(IdeLibraryResource $resource)
    {
        foreach ([$this->libraryList, $this->embeddedLibraryList] as $list) {
            foreach ($list->items as $i => $it) {
                if (FileUtils::equalNames($resource->getPath(), $it->getPath())) {
                    $list->selectedIndex = $i;
                    return;
                }
            }
        }
    }
}