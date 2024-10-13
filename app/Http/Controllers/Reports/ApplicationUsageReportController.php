<?php

namespace App\Http\Controllers\Reports;

class ApplicationUsageReportController
{
    function generateApplicationUsageReport()
    {
        $reportGenerator = new \App\Services\ReportGenerators\ApplicationUsage\Json();
        return $reportGenerator->generate(config('application_usage_report.max_data_age'));
    }
}
