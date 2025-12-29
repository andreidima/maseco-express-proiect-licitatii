<?php

namespace App\Providers;

use App\Models\AppNotification;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Document;
use App\Models\Ltm\Lot;
use App\Observers\Ltm\AuctionObserver;
use App\Observers\Ltm\BidObserver;
use App\Observers\Ltm\DocumentObserver;
use App\Observers\Ltm\LotObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::resourceVerbs([
            'create' => 'adauga',
            'edit' => 'modifica'
        ]);
        Paginator::useBootstrap();
        Model::preventLazyLoading();

        Bid::observe(BidObserver::class);
        Auction::observe(AuctionObserver::class);
        Lot::observe(LotObserver::class);
        Document::observe(DocumentObserver::class);

        View::composer('layouts.app', function ($view) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();
            $reader = AppNotification::readerFor($user);
            $count = 0;

            if (($reader['id'] ?? 0) > 0) {
                $count = AppNotification::query()
                    ->forCurrentUser($user)
                    ->unreadFor($reader['kind'], $reader['id'])
                    ->count();
            }

            $view->with('unreadNotificationsCount', $count);
        });
    }
}
