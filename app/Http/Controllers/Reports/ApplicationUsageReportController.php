<?php

namespace App\Http\Controllers\Reports;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApplicationUsageReportController
{
    function generateApplicationUsageReport(): BinaryFileResponse
    {
        $reportGenerator = new \App\Services\ReportGenerators\ApplicationUsage\Json();
        $reportFilepath = $reportGenerator->generate(config('application_usage_report.max_data_age'));
        return response()->download(
            file: $reportFilepath,
            name: "Отчёт об использовании приложения.json",
        )->deleteFileAfterSend();
    }
}
