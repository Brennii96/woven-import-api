<?php

declare(strict_types=1);

namespace App\Services\Import;

use App\Data\InvestorImportData;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

final class InvestorImportValidator
{
    public function validate(CsvInvestorRecord $record): InvestorImportData
    {
        match (true) {
            $record->investorId < 1 => throw new InvalidArgumentException('investor_id must be a positive integer.'),
            $record->name === '' => throw new InvalidArgumentException('name is required.'),
            mb_strlen($record->name) > 255 => throw new InvalidArgumentException('name must not exceed 255 characters.'),
            $record->age < 0 || $record->age > 150 => throw new InvalidArgumentException('age must be between 0 and 150.'),
            default => null,
        };

        return new InvestorImportData(
            investorId: $record->investorId,
            name: $record->name,
            age: $record->age,
            investmentAmount: $record->investmentAmount,
            investmentDate: $this->parseDate($record->investmentDate),
        );
    }

    private function parseDate(string $value): DateTimeImmutable
    {
        $date = DateTimeImmutable::createFromFormat(
            '!d-m-Y',
            $value,
            new DateTimeZone('UTC'),
        );
        $errors = DateTimeImmutable::getLastErrors();

        if (
            $date === false
            || (is_array($errors) && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
            || $date->format('d-m-Y') !== $value
        ) {
            throw new InvalidArgumentException('investment_date must use a valid DD-MM-YYYY date.');
        }

        return $date;
    }
}
