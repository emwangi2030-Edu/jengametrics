<?php

use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\V1\ActiveProjectController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BomReadController;
use App\Http\Controllers\Api\V1\BqDocumentReadController;
use App\Http\Controllers\Api\V1\DashboardSummaryController;
use App\Http\Controllers\Api\V1\MaterialWriteController;
use App\Http\Controllers\Api\V1\PaymentWriteController;
use App\Http\Controllers\Api\V1\BomWriteController;
use App\Http\Controllers\Api\V1\BqDocumentWriteController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RequisitionWriteController;
use App\Http\Controllers\Api\V1\ReportingController;
use App\Http\Controllers\Api\V1\SupplierWriteController;
use App\Http\Controllers\Api\V1\WorkerWriteController;
use App\Http\Middleware\ResolveActiveProject;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Legacy API endpoints (kept for backward compatibility)
|--------------------------------------------------------------------------
*/
Route::post('register', [UsersController::class, 'register']);
Route::post('login', [UsersController::class, 'login']);
Route::middleware('auth:sanctum')->get('user', [UsersController::class, 'getAuthenticatedUser']);

/*
|--------------------------------------------------------------------------
| API v1 endpoints
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware(\App\Http\Middleware\ApiAuthenticate::class)->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
});

    Route::middleware(\App\Http\Middleware\ApiAuthenticate::class)->group(function () {
        Route::get('profile', [ProfileController::class, 'show']);
        Route::match(['patch', 'post'], 'profile', [ProfileController::class, 'update']);
        Route::post('profile/password', [ProfileController::class, 'updatePassword']);
    });

    Route::middleware([\App\Http\Middleware\ApiAuthenticate::class, ResolveActiveProject::class])->group(function () {
        Route::get('projects/active', [ActiveProjectController::class, 'show']);
        Route::post('projects/active', [ActiveProjectController::class, 'update']);

        Route::get('dashboard/summary', DashboardSummaryController::class);
        Route::get('boq/documents', [BqDocumentReadController::class, 'index']);
        Route::get('boms', [BomReadController::class, 'index']);
        Route::get('reports/summary', [ReportingController::class, 'summary']);
        Route::get('reports/wages', [ReportingController::class, 'wages']);
        Route::get('reports/purchases', [ReportingController::class, 'purchases']);

        Route::post('boq/documents', [BqDocumentWriteController::class, 'store']);
        Route::post('boms', [BomWriteController::class, 'store']);
        Route::post('suppliers', [SupplierWriteController::class, 'store']);
        Route::post('workers', [WorkerWriteController::class, 'store']);
        Route::post('workers/{worker}/payments', [PaymentWriteController::class, 'store']);
        Route::post('materials/adhoc', [MaterialWriteController::class, 'storeAdhoc']);
        Route::post('requisitions/adhoc', [RequisitionWriteController::class, 'storeAdhoc']);
    });
    });
