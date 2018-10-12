<?php

namespace ide\forms;

use ide\formats\ProjectFormat;
use ide\formats\templates\JPPMPackageFileTemplate;
use ide\Ide;
use ide\project\behaviours\BackupProjectBehaviour;
use ide\project\behaviours\JavaPlatformBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;
use ide\project\support\AndroidProjectSupport;
use ide\utils\FileUtils;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXLoader;
use php\gui\UXTextField;
use php\io\Stream;
use php\lang\Process;

/**
 * Class CreateAndroidProjectForm
 * @package ide\forms
 *
 * @property UXButton close
 * @property UXButton create
 *
 * @property UXTextField app_name
 * @property UXTextField app_package
 * @property UXTextField version_string
 * @property UXTextField version_int
 * @property UXTextField sdkVersion
 * @property UXTextField buildToolsVersion
 */
class CreateAndroidProjectForm extends AbstractIdeForm
{
    private $created = false;

    /**
     * @var Project
     */
    private $project;

    public function __construct(Project $project)
    {
        parent::__construct(null);

        $this->project = $project;
    }

    protected function loadDesign()
    {
        $this->layout = (new UXLoader())->loadFromString(Stream::getContents("res://ide/forms/CreateAndroidProjectForm.fxml"));

        $this->title = "Android project options";

        $this->close->on("click", [$this, "onCloseClick"]);
        $this->create->on("click", [$this, "onCreateClick"]);
    }

    public function onCloseClick() {
        $this->hide();
    }

    public function onCreateClick() {
        if ($this->app_name->text
            && $this->app_package->text
            && $this->version_string->text
            && $this->version_int->text
            && $this->sdkVersion->text
            && $this->buildToolsVersion) {
            $this->created = true;

            $this->showPreloader("Creating ...");

            FileUtils::putAsync($this->project->getFile("src/index.php"), "<?php\r\recho 'Hello World';\r");

            $pkgFile = new JPPMPackageFileTemplate($this->project->getFile("package.php.yml"));

            $pkgFile->useProject($this->project);
            $pkgFile->setPlugins(['App']);
            $pkgFile->setIncludes(['index.php']);

            $pkgFile->setDeps([
                'jphp-runtime' => '*',
                'jphp-android-ext' => "*"
            ]);

            $pkgFile->setDevDeps([
                'jppm-android-plugin' => "*"
            ]);

            $pkgFile->save();

            $install = ['jppm', 'install'];

            if (Ide::get()->isWindows())
                $install = flow(['cmd.exe', '/c'], $install)->toArray();

            (new Process($install, $this->project->getRootDir(), Ide::get()->makeEnvironment()))->inheritIO()->startAndWait();

            $app = ['jppm', 'android:init',
                "-{$this->app_name->text}", "-{$this->app_package->text}",
                "-{$this->version_string->text}", "-{$this->version_int->text}",
                "-{$this->sdkVersion->text}", "-{$this->buildToolsVersion->text}"];

            if (Ide::get()->isWindows())
                $app = flow(['cmd.exe', '/c'], $app)->toArray();

            (new Process($app, $this->project->getRootDir(), Ide::get()->makeEnvironment()))->inheritIO()->startAndWait();

            $this->project->register(new JavaPlatformBehaviour());
            $this->project->register(new PhpProjectBehaviour());
            $this->project->register(new BackupProjectBehaviour());

            $this->project->registerFormat(new ProjectFormat());

            $this->hide();
        } else {
            UXDialog::showAndWait("Error values", "ERROR");
        }
    }

    public function showAndWait()
    {
        parent::showAndWait();

        return $this->created;
    }
}
