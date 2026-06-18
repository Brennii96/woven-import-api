<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Data\InvestorSummaryData;
use Illuminate\Http\Request;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, array{investor_id: int, name: string, age: int, investment_amount: float}>
 */
final readonly class InvestorSummaryResourceCollection implements IteratorAggregate
{
    /**
     * @param  iterable<int, InvestorSummaryData>  $investors
     */
    public function __construct(
        private Request $request,
        private iterable $investors,
    ) {}

    /**
     * @return Traversable<int, array{investor_id: int, name: string, age: int, investment_amount: float}>
     */
    public function getIterator(): Traversable
    {
        foreach ($this->investors as $investor) {
            yield (new InvestorSummaryResource($investor))->resolve($this->request);
        }
    }
}
