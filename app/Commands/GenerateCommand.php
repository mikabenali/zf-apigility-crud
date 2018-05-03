<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\GeneratorService;

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

    /* @var GeneratorService */
    private $generatorService;

    /**
     * @return GeneratorService
     */
    public function getGeneratorService(): GeneratorService
    {
        return $this->generatorService;
    }

    /**
     * @param GeneratorService $generatorService
     */
    public function setGeneratorService(GeneratorService $generatorService): void
    {
        $this->generatorService = $generatorService;
    }


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

    public function __construct(GeneratorService $generatorService)
    {
        parent::__construct();

        $this->setGeneratorService($generatorService);
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
        $modules = $this->generatorService->getModules($this->config['projects'][$optionProject]);
        if (count($modules) < 1) {
            $this->task("No modules found", function () { return false;});
            return;
        }
        $optionModule = $this->menu('Chose a module from your projects', $modules)->open();

        $this->generatorService->createClassFiles('Purchase');
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
