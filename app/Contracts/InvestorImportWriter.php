<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\InvestorImportData;

interface InvestorImportWriter
{
    /**
     * @param  iterable<int, InvestorImportData>  $rows
     */
    public function write(iterable $rows, int $chunkSize): int;
}
