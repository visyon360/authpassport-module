<?php

namespace Modules\AuthPassport\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Artisan;

class InstallPassportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module-passport:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install module Passport Auth.';



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Installing passport migrations');
        Artisan::call('migrate');

        $this->info('Installing passport');
        Artisan::call('passport:install');
    }
}
