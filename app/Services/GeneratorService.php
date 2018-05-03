<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Nette\PhpGenerator\ClassType;

class GeneratorService
{
    /* @var array */
    private $config;

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function __construct()
    {
        $this->setConfig(config('config'));
    }

    /**
     * @param string $classType
     * @return string
     */
    public function generatePhpClass(string $classType): string {
        $class = new ClassType('lala');

        $class->setAbstract()
            ->setFinal()
            ->setExtends('ParentClass')
            ->addImplement('Countable')
            ->addTrait('Nette\SmartObject')
            ->addComment("Description of class.\nSecond line\n")
            ->addComment('@property-read Nette\Forms\Form $form');

        return (string) $class;
    }

    /**
     * Create class files
     *
     * @param string $name
     */
    public function createClassFiles(string $name): void {
        foreach ($this->config['classSuffix'] as $type) {
            $fileName = $name . $type;

            if(Storage::put($this->config['outClassPath'] . $fileName . '.php', $this->generatePhpClass($type))) {
                //$this->task($fileName . ' created.', function () { return true;});
            } else {
                //$this->task($fileName . ' not created.', function () { return false;});
            }
        }
    }

    /**
     * Get Project modules
     *
     * @param string $projectPath
     * @return array
     */
    public function getModules(string $projectPath): array {
        $modules = [];

        foreach (array_diff(scandir(
                $projectPath .
                $this->config['modulePath'])
            , array('..', '.')) as $folder) {
            if (is_dir($folder)); {
                $modules[] = $folder;
            }
        }

        return $modules;
    }
}