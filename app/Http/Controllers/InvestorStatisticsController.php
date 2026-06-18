<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\InvestorStatistics;
use Illuminate\Http\JsonResponse;

final class InvestorStatisticsController extends Controller
{
    public function averageAge(InvestorStatistics $statistics): JsonResponse
    {
        return new JsonResponse([
            'average_age' => $statistics->averageAge(),
        ]);
    }

    public function averageInvestmentAmount(InvestorStatistics $statistics): JsonResponse
    {
        return new JsonResponse([
            'average_investment_amount' => $statistics->averageInvestmentAmount(),
        ]);
    }

    public function totalInvestments(InvestorStatistics $statistics): JsonResponse
    {
        return new JsonResponse([
            'total_investments' => $statistics->totalInvestments(),
        ]);
    }
}
