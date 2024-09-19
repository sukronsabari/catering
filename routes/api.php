<?php

use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

Route::get('merchants', [Api\SearchMerchantController::class, 'index']);
