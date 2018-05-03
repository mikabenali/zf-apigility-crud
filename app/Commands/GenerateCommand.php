<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use Nette\PhpGenerator\ClassType;

class GenerateCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'generate';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate';

    /**
     * The projects config.
     *
     * @var array
     */
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
        parent::__construct();

        $this->setConfig(config('config'));
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        // Projects menu
        if (count($this->config) < 1) {
            $this->task("No projects found", function () { return false;});
            return;
        }
        $optionProject = $this->menu('Chose a project', $this->config['projects'])->open();

        // Modules menu
        $modules = $this->getModules($this->config['projects'][$optionProject]);
        if (count($modules) < 1) {
            $this->task("No modules found", function () { return false;});
            return;
        }
        $optionModule = $this->menu('Chose a module from your projects', $modules)->open();

        $this->createClassFiles('Purchase');
    }

    private function generatePhpClass(string $classType): string {
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
    private function createClassFiles(string $name): void {
        foreach ($this->config['classSuffix'] as $type) {
            $fileName = $name . $type;

            if(Storage::put($this->config['outClassPath'] . $fileName . '.php', $this->generatePhpClass($type))) {
                $this->task($fileName . ' created.', function () { return true;});
            } else {
                $this->task($fileName . ' not created.', function () { return false;});
            }
        }
    }

    /**
     * Get Project modules
     *
     * @param string $projectPath
     * @return array
     */
    private function getModules(string $projectPath): array {
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

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
