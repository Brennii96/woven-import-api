<?php

use App\Contracts\InvestorStatistics;
use App\Http\Controllers\InvestorStatisticsController;

it('returns average investor age', function () {
    $statistics = Mockery::mock(InvestorStatistics::class);
    $statistics->shouldReceive('averageAge')
        ->once()
        ->andReturn(42);

    $response = (new InvestorStatisticsController)->averageAge($statistics);

    expect($response->getData(true))->toBe([
        'average_age' => 42,
    ]);
});

it('returns average investment amount', function () {
    $statistics = Mockery::mock(InvestorStatistics::class);
    $money = number_format(123456.78, 2);
    $statistics->shouldReceive('averageInvestmentAmount')
        ->once()
        ->andReturn($money);

    $response = (new InvestorStatisticsController)->averageInvestmentAmount($statistics);

    expect($response->getData(true))->toBe([
        'average_investment_amount' => $money,
    ]);
});

it('returns total investments', function () {
    $statistics = Mockery::mock(InvestorStatistics::class);
    $statistics->shouldReceive('totalInvestments')
        ->once()
        ->andReturn(100000);

    $response = (new InvestorStatisticsController)->totalInvestments($statistics);

    expect($response->getData(true))->toBe([
        'total_investments' => 100000,
    ]);
});

it('returns null averages for empty data', function () {
    $statistics = Mockery::mock(InvestorStatistics::class);
    $statistics->shouldReceive('averageAge')
        ->once()
        ->andReturnNull();
    $statistics->shouldReceive('averageInvestmentAmount')
        ->once()
        ->andReturnNull();

    $controller = new InvestorStatisticsController;

    expect($controller->averageAge($statistics)->getData(true))->toBe([
        'average_age' => null,
    ])->and($controller->averageInvestmentAmount($statistics)->getData(true))->toBe([
        'average_investment_amount' => null,
    ]);
});
