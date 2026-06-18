<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\InvestorSummaryData;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface InvestorSummaryExporter
{
    public function format(): string;

    /**
     * @param  iterable<int, InvestorSummaryData>  $investors
     */
    public function export(iterable $investors): StreamedResponse;
}
