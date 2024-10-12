<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearOldRequestsLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:http-requests:clear-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаляет логи HTTP-запросов, которые хранятся в системе более 73 часов';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expirationTime = now()->subHours(73);
        $this->info('Удаляю устаревшие логи HTTP-запросов...');
        LogRequest::where('created_at', '<', $expirationTime)->delete();
    }
}
