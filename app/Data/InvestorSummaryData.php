<?php

declare(strict_types=1);

namespace App\Data;

final readonly class InvestorSummaryData
{
    public function __construct(
        public int $investorId,
        public string $name,
        public int $age,
        public float $investmentAmount,
    ) {}
}
