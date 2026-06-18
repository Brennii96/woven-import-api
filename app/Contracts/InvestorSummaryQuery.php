<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\InvestorSummaryData;

interface InvestorSummaryQuery
{
    /**
     * @return iterable<int, InvestorSummaryData>
     */
    public function stream(): iterable;
}
