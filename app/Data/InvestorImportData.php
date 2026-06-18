<?php

declare(strict_types=1);

namespace App\Data;

use DateTimeImmutable;

final readonly class InvestorImportData
{
    public function __construct(
        public int $investorId,
        public string $name,
        public int $age,
        public float $investmentAmount,
        public DateTimeImmutable $investmentDate,
    ) {}
}
