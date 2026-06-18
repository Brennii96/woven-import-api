<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Data\InvestorSummaryData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin InvestorSummaryData
 */
class InvestorSummaryResource extends JsonResource
{
    /**
     * @return array{investor_id: int, name: string, age: int, investment_amount: float}
     */
    public function toArray(Request $request): array
    {
        return [
            'investor_id' => $this->investorId,
            'name' => $this->name,
            'age' => $this->age,
            'investment_amount' => $this->investmentAmount,
        ];
    }
}
