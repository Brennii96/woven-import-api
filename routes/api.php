<?php

use App\Http\Controllers\ImportInvestorsController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvestorStatisticsController;
use Illuminate\Support\Facades\Route;

Route::prefix('investors')
    ->name('investors.')
    ->group(function (): void {
        Route::post('/import', ImportInvestorsController::class)->name('imports.store');
        Route::get('/', [InvestorController::class, 'index'])->name('index');
        Route::get('/exports/{format}', [InvestorController::class, 'export'])->name('exports.show');

        Route::prefix('statistics')
            ->name('statistics.')
            ->controller(InvestorStatisticsController::class)
            ->group(function (): void {
                Route::get('/average-age', 'averageAge')->name('average-age');
            });
    });

Route::prefix('investments/statistics')
    ->name('investments.statistics.')
    ->controller(InvestorStatisticsController::class)
    ->group(function (): void {
        Route::get('/average-amount', 'averageInvestmentAmount')->name('average-amount');
        Route::get('/total', 'totalInvestments')->name('total');
    });
