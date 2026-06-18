<?php

use App\Http\Controllers\ImportInvestorsController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvestorStatisticsController;
use Illuminate\Support\Facades\Route;

Route::post('/investors/import', ImportInvestorsController::class);
Route::get('/investors', [InvestorController::class, 'index']);
Route::get('/investors/export/{format}', [InvestorController::class, 'export']);

Route::prefix('statistics')
    ->controller(InvestorStatisticsController::class)
    ->group(function (): void {
        Route::get('/average-age', 'averageAge');
        Route::get('/average-amount', 'averageInvestmentAmount');
        Route::get('/total-investments', 'totalInvestments');
    });
