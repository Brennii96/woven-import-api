<?php

declare(strict_types=1);

namespace App\Contracts;

interface InvestorStatistics
{
    public function averageAge(): ?int;

    public function averageInvestmentAmount(): ?string;

    public function totalInvestments(): int;
}
