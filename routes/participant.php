<?php

use App\Http\Controllers\Participant\ParticipantAuctionController;
use App\Http\Controllers\Participant\ParticipantOfferController;
use App\Http\Controllers\Participant\ParticipantProfileController;
use App\Http\Controllers\Participant\SupportController;
use Illuminate\Support\Facades\Route;

Route::prefix('participant')
    ->name('participant.')
    ->middleware('checkUserRole:Participant licitatii')
    ->group(function () {
        Route::get('licitatii', [ParticipantAuctionController::class, 'index'])->name('licitatii.index');
        Route::get('licitatii/{auction}', [ParticipantAuctionController::class, 'show'])->name('licitatii.show');

        Route::middleware('ensureParticipantHasCarrier')->group(function () {
            Route::get('oferte', [ParticipantOfferController::class, 'index'])->name('oferte.index');
            Route::get('oferte/adauga', [ParticipantOfferController::class, 'create'])->name('oferte.create');
            Route::post('oferte', [ParticipantOfferController::class, 'store'])->name('oferte.store');
            Route::get('oferte/{bid}/modifica', [ParticipantOfferController::class, 'edit'])->name('oferte.edit');
            Route::put('oferte/{bid}', [ParticipantOfferController::class, 'update'])->name('oferte.update');
        });

        Route::get('profil', [ParticipantProfileController::class, 'edit'])->name('profil.edit');
        Route::put('profil', [ParticipantProfileController::class, 'update'])->name('profil.update');

        Route::prefix('suport')->name('support.')->group(function () {
            Route::get('/', [SupportController::class, 'index'])->name('index');
            Route::get('creaza', [SupportController::class, 'create'])->name('create');
            Route::post('/', [SupportController::class, 'store'])->name('store');
            Route::get('{support_thread}', [SupportController::class, 'show'])->name('show');
            Route::post('{support_thread}/mesaje', [SupportController::class, 'storeMessage'])->name('messages.store');
        });
    });
