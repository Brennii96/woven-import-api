<?php

use App\Services\Import\CsvInvestorRecord;
use App\Services\Import\InvestorImportValidator;

function csvInvestorRecord(
    int $investorId = 1001,
    string $name = 'Daniel Nelson',
    int $age = 28,
    float $investmentAmount = 100.50,
    string $investmentDate = '13-11-2024',
): CsvInvestorRecord {
    $record = new CsvInvestorRecord;
    $record->investorId = $investorId;
    $record->name = $name;
    $record->age = $age;
    $record->investmentAmount = $investmentAmount;
    $record->investmentDate = $investmentDate;

    return $record;
}

it('maps a valid CSV record to import data', function () {
    $data = (new InvestorImportValidator)->validate(csvInvestorRecord());

    expect($data->investorId)->toBe(1001)
        ->and($data->name)->toBe('Daniel Nelson')
        ->and($data->age)->toBe(28)
        ->and($data->investmentAmount)->toBe(100.50)
        ->and($data->investmentDate->format('Y-m-d'))->toBe('2024-11-13');
});

it('rejects invalid investor values', function (
    CsvInvestorRecord $record,
    string $message,
) {
    expect(fn () => (new InvestorImportValidator)->validate($record))
        ->toThrow(InvalidArgumentException::class, $message);
})->with([
    'non-positive id' => [fn () => csvInvestorRecord(investorId: 0), 'investor_id must be a positive integer.'],
    'empty name' => [fn () => csvInvestorRecord(name: ''), 'name is required.'],
    'long name' => [fn () => csvInvestorRecord(name: str_repeat('A', 256)), 'name must not exceed 255 characters.'],
    'negative age' => [fn () => csvInvestorRecord(age: -1), 'age must be between 0 and 150.'],
    'age above maximum' => [fn () => csvInvestorRecord(age: 151), 'age must be between 0 and 150.'],
    'invalid date' => [fn () => csvInvestorRecord(investmentDate: '31-02-2024'), 'investment_date must use a valid DD-MM-YYYY date.'],
]);
