<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationsController;
use App\Http\Controllers\RegisterVaccinationsController;
use App\Http\Controllers\SpotsController;
use App\Http\Middleware\TokenValidation;
use App\Models\Consultations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/auth/login', [AuthController::class, 'login']);
Route::post('v1/auth/logout', [AuthController::class, 'logout']);

Route::middleware(TokenValidation::class)->group(function() {

    Route::post('v1/consultations', [ConsultationsController::class, 'store']);
    Route::get('v1/consultations', [ConsultationsController::class, 'show']);

    Route::get('v1/spots', [SpotsController::class, 'index']);
    Route::get('v1/spots/{spot_id}', [SpotsController::class, 'show']);
    
    Route::post('v1/vaccinations', [RegisterVaccinationsController::class, 'store']);
    Route::get('v1/vaccinations', [RegisterVaccinationsController::class, 'index']);
});