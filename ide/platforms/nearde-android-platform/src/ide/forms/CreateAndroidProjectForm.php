<?php

namespace ide\forms;

use ide\formats\ProjectFormat;
use ide\formats\templates\JPPMPackageFileTemplate;
use ide\Ide;
use ide\project\behaviours\BackupProjectBehaviour;
use ide\project\behaviours\JavaPlatformBehaviour;
use ide\project\behaviours\PhpProjectBehaviour;
use ide\project\Project;
use ide\utils\FileUtils;
use ide\utils\Json;
use php\gui\UXButton;
use php\gui\UXDialog;
use php\gui\UXLoader;
use php\gui\UXTextField;
use php\io\Stream;
use php\lang\Process;
use php\lib\fs;
use php\lib\str;

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

    /**
     * @throws \php\io\IOException
     */
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

    /**
     * @throws \php\io\IOException
     * @throws \php\lang\IllegalArgumentException
     * @throws \php\lang\IllegalStateException
     */
    public function onCreateClick() {
        if ($this->app_name->text
            && $this->app_package->text
            && $this->version_string->text
            && $this->version_int->text
            && $this->sdkVersion->text
            && $this->buildToolsVersion) {
            $this->created = true;

            $this->showPreloader("Creating ...");

            FileUtils::putAsync($this->project->getFile("src/index.php"), Stream::getContents("res://.data/android/src/index.php"));

            $pkgFile = new JPPMPackageFileTemplate($this->project->getFile("package.php.yml"));

            $pkgFile->useProject($this->project);
            $pkgFile->setPlugins([
                'App', "Gradle"
            ]);

            $pkgFile->setIncludes(['index.php']);

            $pkgFile->setDeps([
                'jphp-runtime' => '*',
                'jphp-android-ext' => "*"
            ]);

            $pkgFile->setDevDeps([
                'jppm-android-plugin' => "*"
            ]);

            $pkgFile->save();

            $install = ['jppm', 'install', '-gradle'];

            if (Ide::get()->isWindows())
                $install = flow(['cmd.exe', '/c'], $install)->toArray();

            (new Process($install, $this->project->getRootDir(), Ide::get()->makeEnvironment()))->inheritIO()->startAndWait();

            $pkgFile->setPlugins([
                'App'
            ]);
            $pkgFile->save();

            FileUtils::put($this->project->getFile("gradle/wrapper/gradle-wrapper.properties")->getAbsolutePath(), str::join([
                "distributionBase=GRADLE_USER_HOME",
                "distributionPath=wrapper/dists",
                "zipStoreBase=GRADLE_USER_HOME",
                "zipStorePath=wrapper/dists",
                "distributionUrl=https\://services.gradle.org/distributions/gradle-4.6-bin.zip"
            ], "\n"));

            $sdk = $this->sdkVersion->text;

            $settings = [
                "compileSdkVersion" => $sdk,
                "buildToolsVersion" => $this->buildToolsVersion->text,
                "targetSdkVersion" => $sdk,
                "appName" => $this->app_name->text,
                "applicationId" => $this->app_package->text,
                "versionCode" => (int) $this->version_int->text,
                "versionName" => $this->version_string->text,
            ];

            $script = Stream::getContents("res://.data/android/build.groovy");
            $xml = Stream::getContents("res://.data/android/resources/AndroidManifest.xml");

            foreach ($settings as $key => $val)
                $script = str::replace($script, "%{$key}%", $val);

            foreach ($settings as $key => $val)
                $xml = str::replace($xml, "%{$key}%", $val);

            fs::makeDir($this->project->getFile("resources")->getAbsolutePath());
            fs::makeFile($this->project->getFile("resources/AndroidManifest.xml")->getAbsolutePath());

            Stream::putContents($this->project->getFile("build.gradle")->getAbsolutePath(), $script);
            Stream::putContents($this->project->getFile("resources/AndroidManifest.xml")->getAbsolutePath(), $xml);
            FileUtils::copyFile("res://.data/jphp/compiler.jar", $this->project->getFile(".venity/compiler.jar")->getAbsolutePath());

            fs::makeFile($this->project->getIdeFile("tasks.json")->getAbsolutePath());
            Stream::putContents($this->project->getIdeFile("tasks.json")->getAbsolutePath(), Json::encode([
                "gradle-packageDebug" => [
                    "type" => "android",
                    "task" => "packageDebug",
                    "title" => "packageDebug task"

                ],
                "jppm-update" => [
                    "type" => "jppm",
                    "task" => "update",
                    "title" => "jppm update"
                ]
            ]));

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