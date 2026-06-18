<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\InvestorImportReader;
use App\Contracts\InvestorImportWriter;
use App\Contracts\InvestorStatistics;
use App\Contracts\InvestorSummaryQuery;
use App\Exports\CsvInvestorSummaryExporter;
use App\Exports\InvestorSummaryExportManager;
use App\Services\Import\BulkInvestorImportWriter;
use App\Services\Import\CsvInvestorImportReader;
use App\Services\Import\InvestorImportService;
use App\Services\InvestorStatisticsService;
use App\Services\InvestorSummaryService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(InvestorImportReader::class, CsvInvestorImportReader::class);
        $this->app->bind(InvestorImportWriter::class, BulkInvestorImportWriter::class);
        $this->app->bind(InvestorStatistics::class, InvestorStatisticsService::class);
        $this->app->bind(InvestorSummaryQuery::class, InvestorSummaryService::class);
        $this->app->singleton(
            InvestorSummaryExportManager::class,
            fn (Application $app): InvestorSummaryExportManager => new InvestorSummaryExportManager([
                $app->make(CsvInvestorSummaryExporter::class),
            ]),
        );
        $this->app->bind(
            InvestorImportService::class,
            fn (Application $app): InvestorImportService => new InvestorImportService(
                reader: $app->make(InvestorImportReader::class),
                writer: $app->make(InvestorImportWriter::class),
                chunkSize: max(1, (int) config('imports.investors.chunk_size', 1000)),
            ),
        );
    }

    public function boot(): void {}
}
