<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\InvestorStatistics;
use App\Models\Investment;
use App\Models\Investor;

final readonly class InvestorStatisticsService implements InvestorStatistics
{
    public function averageAge(): ?int
    {
        $average = Investor::query()->avg('age');

        return $average === null ? null : (int) $average;
    }

    public function averageInvestmentAmount(): ?string
    {
        $average = Investment::query()->avg('investment_amount');

        return $average === null ? null : number_format((float) $average, 2);
    }

    public function totalInvestments(): int
    {
        return Investment::query()->count();
    }
}
