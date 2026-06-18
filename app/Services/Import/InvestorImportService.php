<?php

declare(strict_types=1);

namespace App\Services\Import;

use App\Contracts\InvestorImportReader;
use App\Contracts\InvestorImportWriter;
use Illuminate\Http\UploadedFile;

final readonly class InvestorImportService
{
    public function __construct(
        private InvestorImportReader $reader,
        private InvestorImportWriter $writer,
        private int $chunkSize,
    ) {}

    public function import(UploadedFile $file): int
    {
        return $this->writer->write(
            $this->reader->records($file),
            $this->chunkSize,
        );
    }
}
