<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\InvestorSummaryQuery;
use App\Data\InvestorSummaryData;
use App\Models\Investor;
use Illuminate\Database\Eloquent\Builder;

final readonly class InvestorSummaryService implements InvestorSummaryQuery
{
    /**
     * @return iterable<int, InvestorSummaryData>
     */
    public function stream(): iterable
    {
        foreach (
            $this->query()->lazyById(
                column: 'investor_id',
            ) as $investor
        ) {
            yield $this->map($investor);
        }
    }

    /**
     * @return Builder<Investor>
     */
    private function query(): Builder
    {
        return Investor::query()
            ->select([
                'investor_id',
                'name',
                'age',
            ])
            ->withSum('investments as investment_amount', 'investment_amount')
            ->orderBy('investor_id');
    }

    private function map(Investor $investor): InvestorSummaryData
    {
        return new InvestorSummaryData(
            investorId: (int) $investor->getAttribute('investor_id'),
            name: (string) $investor->getAttribute('name'),
            age: (int) $investor->getAttribute('age'),
            investmentAmount: (float) ($investor->getAttribute('investment_amount') ?? 0),
        );
    }
}
