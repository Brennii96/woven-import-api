<?php

use App\Exceptions\InvestorImportValidationException;
use App\Services\Import\CsvInvestorImportReader;
use App\Services\Import\InvestorImportValidator;
use Illuminate\Http\UploadedFile;

function csvUpload(string $contents): UploadedFile
{
    $path = tempnam(sys_get_temp_dir(), 'investor-csv-');
    file_put_contents($path, $contents);

    return new UploadedFile($path, 'investors.csv', 'text/csv', test: true);
}

it('maps CSV records to typed rows', function () {
    $file = csvUpload(
        "investor_id,name,age,investment_amount,investment_date\n".
        "1001, Daniel Nelson ,28,328085.43,13-11-2024\n",
    );

    $rows = iterator_to_array((new CsvInvestorImportReader(new InvestorImportValidator))->records($file));
    $row = $rows[2];

    expect($row->investorId)->toBe(1001)
        ->and($row->name)->toBe('Daniel Nelson')
        ->and($row->age)->toBe(28)
        ->and($row->investmentAmount)->toBe(328085.43)
        ->and($row->investmentDate->format('Y-m-d'))->toBe('2024-11-13');
});

it('rejects incorrect headers', function () {
    $file = csvUpload(
        "id,name,age,investment_amount,investment_date\n".
        "1001,Daniel Nelson,28,328085.43,13-11-2024\n",
    );

    expect(fn () => iterator_to_array((new CsvInvestorImportReader(new InvestorImportValidator))->records($file)))
        ->toThrow(InvestorImportValidationException::class, 'CSV headers must exactly match');
});

it('reports line number for invalid records', function () {
    $file = csvUpload(
        "investor_id,name,age,investment_amount,investment_date\n".
        "1001,Daniel Nelson,28,328085.43,13-11-2024\n".
        "1002,Invalid Age,151,100.00,14-11-2024\n",
    );

    expect(fn () => iterator_to_array((new CsvInvestorImportReader(new InvestorImportValidator))->records($file)))
        ->toThrow(InvestorImportValidationException::class, 'Row 3: age must be between 0 and 150.');
});

it('rejects invalid numeric values', function () {
    $file = csvUpload(
        "investor_id,name,age,investment_amount,investment_date\n".
        "1001,Daniel Nelson,28,not-a-number,13-11-2024\n",
    );

    expect(fn () => iterator_to_array((new CsvInvestorImportReader(new InvestorImportValidator))->records($file)))
        ->toThrow(InvestorImportValidationException::class, 'Row 2:');
});

it('streams no records from a header-only CSV', function () {
    $file = csvUpload(
        "investor_id,name,age,investment_amount,investment_date\n",
    );

    expect(iterator_to_array((new CsvInvestorImportReader(new InvestorImportValidator))->records($file)))->toBe([]);
});
