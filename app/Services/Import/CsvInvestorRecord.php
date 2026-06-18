<?php

declare(strict_types=1);

namespace App\Services\Import;

use League\Csv\Serializer\MapCell;

final class CsvInvestorRecord
{
    #[MapCell(column: 'investor_id', trimFieldValueBeforeCasting: true)]
    public int $investorId;

    #[MapCell(column: 'name', trimFieldValueBeforeCasting: true)]
    public string $name;

    #[MapCell(column: 'age', trimFieldValueBeforeCasting: true)]
    public int $age;

    #[MapCell(column: 'investment_amount', trimFieldValueBeforeCasting: true)]
    public float $investmentAmount;

    #[MapCell(column: 'investment_date', trimFieldValueBeforeCasting: true)]
    public string $investmentDate;
}
