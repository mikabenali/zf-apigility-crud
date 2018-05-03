<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

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
     * Module path
     */
    const MODULE_PATH = '/module';

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

        $this->setConfig(config('projects'));
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
        $optionProject = $this->menu('Chose a project', $this->config)->open();

        // Modules menu
        $modules = $this->getModules($this->config[$optionProject]);
        if (count($modules) < 1) {
            $this->task("No modules found", function () { return false;});
            return;
        }
        $optionModule = $this->menu('Chose a module from your projects', $modules)->open();
    }

    private function getModules(string $projectPath): array {
        if (!$modules = scandir($projectPath . $this::MODULE_PATH)) {
           return false;
        }

        return array_filter($modules, function($file) {
            //return is_dir($file);
            return true;
        });
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
