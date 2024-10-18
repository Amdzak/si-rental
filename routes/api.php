<?php

use App\Http\Controllers\Api\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [ApiAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']);
Route::apiResource('/user', \App\Http\Controllers\Api\ApiUserController::class);
Route::apiResource('/schedule', \App\Http\Controllers\Api\ApiScheduleController::class);
Route::apiResource('/machine', \App\Http\Controllers\Api\ApiMachinesController::class);
Route::apiResource('/sparepart', \App\Http\Controllers\Api\ApiSparepartController::class);
Route::apiResource('/repair-report', \App\Http\Controllers\Api\ApiRepairReportController::class);
Route::apiResource('/damage-report', \App\Http\Controllers\Api\ApiDamageReportController::class);