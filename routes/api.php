<?php

use App\Http\Controllers\ImportInvestorsController;
use Illuminate\Support\Facades\Route;

Route::post('/investors/import', ImportInvestorsController::class);
