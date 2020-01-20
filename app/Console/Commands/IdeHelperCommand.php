<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class IdeHelperCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ide-helper:all';

    /**
     * The console command description.
     */
    protected $description = '一键执行所有的 IDE Helper 命令';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Artisan::call('clear-compiled');

        try {
            \Artisan::call('ide-helper:generate');
            \Artisan::call('ide-helper:meta');
            \Artisan::call('ide-helper:models --nowrite');
        } catch (CommandNotFoundException $e) {
            // Do nothing
        }
    }
}
