<?php

declare(strict_types=1);

namespace App\Services\Import;

use League\Csv\Serializer\MapCell;

final class CsvInvestorRecord
{
    #[MapCell(column: 'investor_id', trimFieldValueBeforeCasting: true)]
    public ?int $investorId = null;

    #[MapCell(column: 'name', trimFieldValueBeforeCasting: true)]
    public ?string $name = null;

    #[MapCell(column: 'age', trimFieldValueBeforeCasting: true)]
    public ?int $age = null;

    #[MapCell(column: 'investment_amount', trimFieldValueBeforeCasting: true)]
    public ?float $investmentAmount = null;

    #[MapCell(column: 'investment_date', trimFieldValueBeforeCasting: true)]
    public ?string $investmentDate = null;
}
