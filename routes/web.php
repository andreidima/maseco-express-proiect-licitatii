<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Settings\CurrencyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Support\AdminSupportController;
use App\Http\Controllers\Tech\CronJobLogController;
use App\Http\Controllers\Tech\ImpersonationController;
use App\Http\Controllers\Tech\MigrationController;
use App\Http\Controllers\ReportController;


Auth::routes(['register' => false, 'password.request' => false, 'reset' => false]);

Route::post('/limba', [LocaleController::class, 'update'])->name('locale.update');

Route::redirect('/', '/acasa');

Route::middleware(['auth', 'checkUserActiv'])->group(function () {
    Route::get('/acasa', [HomeController::class, 'index'])->name('acasa');

    Route::prefix('notificari')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/citeste-tot', [NotificationController::class, 'markAllRead'])->name('read_all');
        Route::get('/{notification}', [NotificationController::class, 'show'])->name('show');
        Route::post('/{notification}/citeste', [NotificationController::class, 'markRead'])->name('read');
    });

    Route::resource('/utilizatori', UserController::class)->parameters(['utilizatori' => 'user'])->names('users')
        ->middleware('checkUserRole:Admin,SuperAdmin');

    Route::prefix('setari')->name('settings.')->middleware('checkUserRole:SuperAdmin,Admin,Operator')->group(function () {
        Route::resource('monede', CurrencyController::class)
            ->parameters(['monede' => 'currency'])
            ->names('currencies')
            ->except(['show']);
    });

    Route::prefix('tech')->name('tech.')->middleware('checkUserRole:SuperAdmin')->group(function () {
        Route::get('impersonare', [ImpersonationController::class, 'index'])->name('impersonation.index');
    Route::post('impersonare/{user}', [ImpersonationController::class, 'impersonate'])->name('impersonation.start');

    Route::get('cronjobs', [CronJobLogController::class, 'index'])->name('cronjobs.index');

        Route::get('migratii', [MigrationController::class, 'index'])->name('migrations.index');
        Route::post('migratii/ruleaza', [MigrationController::class, 'run'])->name('migrations.run');
        Route::post('migratii/{migration}/anuleaza', [MigrationController::class, 'undo'])->name('migrations.undo');
    });

    Route::post('impersonare/opreste', [ImpersonationController::class, 'stop'])
        ->name('tech.impersonation.stop');

    Route::prefix('suport')
        ->name('support.admin.')
        ->middleware('checkUserRole:SuperAdmin,Admin,Operator')
        ->group(function () {
            Route::get('/', [AdminSupportController::class, 'index'])->name('index');
            Route::get('{support_thread}', [AdminSupportController::class, 'show'])->name('show');
            Route::post('{support_thread}/mesaje', [AdminSupportController::class, 'storeMessage'])->name('messages.store');
        });

    Route::prefix('rapoarte')->name('reports.')->middleware('checkUserRole:SuperAdmin,Admin,Operator')->group(function () {
        Route::get('/licitatii', [ReportController::class, 'auctions'])->name('auctions');
        Route::get('/oferte', [ReportController::class, 'bids'])->name('bids');
        Route::get('/contracte', [ReportController::class, 'contracts'])->name('contracts');
        Route::get('/utilizatori', [ReportController::class, 'users'])->name('users');
        Route::get('/suport', [ReportController::class, 'support'])->name('support');
        Route::get('/date-baza', [ReportController::class, 'masterData'])->name('master_data');
    });

    require __DIR__ . '/ltm.php';
    require __DIR__ . '/participant.php';
});
