<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\InvestorImportData;
use Illuminate\Http\UploadedFile;

interface InvestorImportReader
{
    /**
     * @return iterable<int, InvestorImportData>
     */
    public function records(UploadedFile $file): iterable;
}
