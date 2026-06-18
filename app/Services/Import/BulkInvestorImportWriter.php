<?php

declare(strict_types=1);

namespace App\Services\Import;

use App\Contracts\InvestorImportWriter;
use App\Data\InvestorImportData;
use App\Exceptions\InvestorImportValidationException;
use App\Models\Investment;
use App\Models\Investor;
use Illuminate\Support\Facades\DB;

final class BulkInvestorImportWriter implements InvestorImportWriter
{
    /**
     * @param  iterable<int, InvestorImportData>  $rows
     *
     * @throws \Throwable
     */
    public function write(iterable $rows, int $chunkSize): int
    {
        return DB::transaction(function () use ($rows, $chunkSize): int {
            $investors = [];
            $investments = [];
            $seenInvestments = [];
            $rowCount = 0;

            foreach ($rows as $lineNumber => $row) {
                $investmentDate = $row->investmentDate->format('Y-m-d');
                $investmentKey = $row->investorId.'|'.$investmentDate;

                if (isset($seenInvestments[$investmentKey])) {
                    throw InvestorImportValidationException::forRow(
                        $lineNumber,
                        "Duplicate investment for investor $row->investorId on $investmentDate; first declared on row $seenInvestments[$investmentKey].",
                    );
                }

                $seenInvestments[$investmentKey] = $lineNumber;
                $investors[$row->investorId] = [
                    'investor_id' => $row->investorId,
                    'name' => $row->name,
                    'age' => $row->age,
                ];
                $investments[$investmentKey] = [
                    'investor_id' => $row->investorId,
                    'investment_amount' => $row->investmentAmount,
                    'investment_date' => $investmentDate,
                ];
                $rowCount++;

                if (count($investments) >= $chunkSize) {
                    $this->upsert($investors, $investments);
                    $investors = [];
                    $investments = [];
                }
            }

            $this->upsert($investors, $investments);

            if ($rowCount === 0) {
                throw InvestorImportValidationException::forFile(
                    'CSV must contain at least one data row.',
                );
            }

            return $rowCount;
        }, attempts: 3);
    }

    /**
     * @param  array<int, array{investor_id: int, name: string, age: int}>  $investors
     * @param  array<string, array{investor_id: int, investment_amount: float, investment_date: string}>  $investments
     */
    private function upsert(array $investors, array $investments): void
    {
        if ($investors === []) {
            return;
        }

        Investor::query()->upsert(
            array_values($investors),
            uniqueBy: ['investor_id'],
            update: ['name', 'age'],
        );

        Investment::query()->upsert(
            array_values($investments),
            uniqueBy: ['investor_id', 'investment_date'],
            update: ['investment_amount'],
        );
    }
}
