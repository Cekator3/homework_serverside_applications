<?php

namespace App\Jobs;

use DateTime;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateApplicationUsageReport implements ShouldQueue
{
    use Queueable;

    /**
     * Indicate if the job should be marked as failed on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = true;


    /**
     * Determine number of times the job may be attempted.
     */
    public function tries(): int
    {
        return (int)config('application_usage_report.max_attempts');
    }


    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addMinutes((int)config('application_usage_report.max_execution_time'));
    }


    /**
    * Calculate the number of seconds to wait before retrying the job.
    */
    public function backoff(): int
    {
        return (int)config('application_usage_report.attempt_timeout');
    }


    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }


    private function sendReportToAdmins(string $reportFilepath): void
    {
        // Получение списка администраторов
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        Mail::to($admins)
            ->sendNow(new \App\Mail\ApplicationUsageReport($reportFilepath));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reportGenerator = new \App\Services\ReportGenerators\ApplicationUsage\Json();
        $reportFilepath = $reportGenerator->generate(config('application_usage_report.max_data_age'));
        $this->sendReportToAdmins($reportFilepath);
        unlink($reportFilepath);
    }
}
