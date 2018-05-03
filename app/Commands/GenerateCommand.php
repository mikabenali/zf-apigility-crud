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
        $option = $this->menu('Chose a project', array_keys($this->config))->open();
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
