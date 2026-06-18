<?php

declare(strict_types=1);

namespace App\Exports;

use App\Contracts\InvestorSummaryExporter;
use App\Data\InvestorSummaryData;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class InvestorSummaryExportManager
{
    /**
     * @var array<string, InvestorSummaryExporter>
     */
    private array $exporters;

    /**
     * @param  iterable<int, InvestorSummaryExporter>  $exporters
     */
    public function __construct(iterable $exporters)
    {
        $mapped = [];

        foreach ($exporters as $exporter) {
            $mapped[$exporter->format()] = $exporter;
        }

        $this->exporters = $mapped;
    }

    public function supports(string $format): bool
    {
        return isset($this->exporters[$format]);
    }

    /**
     * @param  iterable<int, InvestorSummaryData>  $investors
     */
    public function export(string $format, iterable $investors): StreamedResponse
    {
        return ($this->exporters[$format]
            ?? throw new InvalidArgumentException("Unsupported export format [$format]."))
            ->export($investors);
    }
}
