<?php

use App\Http\Controllers\Ltm\LtmAuctionController;
use App\Http\Controllers\Ltm\LtmBidController;
use App\Http\Controllers\Ltm\LtmCarrierController;
use App\Http\Controllers\Ltm\LtmClientController;
use App\Http\Controllers\Ltm\LtmContractController;
use App\Http\Controllers\Ltm\LtmDashboardController;
use App\Http\Controllers\Ltm\LtmDocumentController;
use App\Http\Controllers\Ltm\LtmDriverController;
use App\Http\Controllers\Ltm\LtmLotController;
use App\Http\Controllers\Ltm\LtmRouteController;
use App\Http\Controllers\Ltm\LtmTruckController;
use Illuminate\Support\Facades\Route;

Route::prefix('licitatii-transport-marfuri')
    ->name('ltm.')
    ->middleware('checkUserRole:SuperAdmin,Admin,Operator')
    ->group(function () {
        Route::get('/panou', [LtmDashboardController::class, 'index'])->name('dashboard');
        Route::resource('licitatii', LtmAuctionController::class)->parameters(['licitatii' => 'auction']);
        Route::resource('loturi', LtmLotController::class)->parameters(['loturi' => 'lot']);
        Route::resource('clienti', LtmClientController::class)->parameters(['clienti' => 'client']);
        Route::resource('transportatori', LtmCarrierController::class)->parameters(['transportatori' => 'carrier']);
        Route::resource('curse', LtmRouteController::class)->parameters(['curse' => 'route']);
        Route::resource('oferte', LtmBidController::class)->parameters(['oferte' => 'bid']);
        Route::resource('contracte', LtmContractController::class)->parameters(['contracte' => 'contract']);
        Route::resource('camioane', LtmTruckController::class)->parameters(['camioane' => 'truck']);
        Route::resource('soferi', LtmDriverController::class)->parameters(['soferi' => 'driver']);
        Route::resource('documente', LtmDocumentController::class)->parameters(['documente' => 'document']);
    });
