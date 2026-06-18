<?php

use App\Contracts\InvestorImportReader;
use App\Contracts\InvestorImportWriter;
use App\Data\InvestorImportData;
use App\Services\Import\InvestorImportService;
use Illuminate\Http\UploadedFile;

it('passes reader records and configured chunk size to writer', function () {
    $rows = [
        new InvestorImportData(
            investorId: 1001,
            name: 'Daniel Nelson',
            age: 28,
            investmentAmount: 100.50,
            investmentDate: new DateTimeImmutable('2024-11-13'),
        ),
    ];

    $file = new UploadedFile(__FILE__, 'investors.csv', 'text/csv', test: true);
    $reader = Mockery::mock(InvestorImportReader::class);
    $writer = Mockery::mock(InvestorImportWriter::class);

    $reader->shouldReceive('records')
        ->once()
        ->with($file)
        ->andReturn($rows);

    $writer->shouldReceive('write')
        ->once()
        ->with($rows, 250)
        ->andReturn(1);

    $service = new InvestorImportService($reader, $writer, chunkSize: 250);

    expect($service->import($file))->toBe(1);
});
