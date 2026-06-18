<?php

declare(strict_types=1);

namespace App\Exports;

use App\Contracts\InvestorSummaryExporter;
use App\Data\InvestorSummaryData;
use League\Csv\Writer;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class CsvInvestorSummaryExporter implements InvestorSummaryExporter
{
    public function format(): string
    {
        return 'csv';
    }

    /**
     * @param  iterable<int, InvestorSummaryData>  $investors
     */
    public function export(iterable $investors): StreamedResponse
    {
        return new StreamedResponse(
            callbackOrChunks: function () use ($investors): void {
                $stream = fopen('php://output', 'w');

                if ($stream === false) {
                    throw new RuntimeException('Unable to open CSV output stream.');
                }

                $writer = Writer::from($stream);
                $writer->insertOne([
                    'investor_id',
                    'name',
                    'age',
                    'investment_amount',
                ]);

                foreach ($investors as $investor) {
                    $writer->insertOne([
                        $investor->investorId,
                        $investor->name,
                        $investor->age,
                        number_format($investor->investmentAmount, 2, '.', ''),
                    ]);
                }
            },
            headers: [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="investors.csv"',
                'X-Accel-Buffering' => 'no',
            ],
        );
    }
}
