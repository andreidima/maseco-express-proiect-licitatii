<?php

namespace Database\Seeders;

use App\Models\AppNotification;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Carrier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('notifications')) {
            return;
        }

        if (AppNotification::query()->exists()) {
            return;
        }

        $auction = Auction::query()->orderBy('id')->first();
        $carrier = Carrier::query()->orderBy('id')->first();

        if ($auction) {
            AppNotification::create([
                'audience' => 'admin',
                'type' => 'auction.created',
                'auction_id' => $auction->id,
                'context' => trim(($auction->auction_number ?? '') . ' ' . ($auction->title ?? '')),
                'data' => [
                    'auction_number' => $auction->auction_number,
                    'auction_title' => $auction->title,
                ],
            ]);
        }

        if ($auction && $carrier) {
            AppNotification::create([
                'audience' => 'admin',
                'type' => 'bid.created',
                'auction_id' => $auction->id,
                'carrier_id' => $carrier->id,
                'context' => trim(($auction->auction_number ?? '') . ' ' . ($carrier->name ?? '')),
                'data' => [
                    'auction_number' => $auction->auction_number,
                    'auction_title' => $auction->title,
                    'carrier_name' => $carrier->name,
                    'lot_code' => 'DEMO',
                ],
            ]);

            AppNotification::create([
                'audience' => 'carrier',
                'type' => 'bid.status_rejected',
                'auction_id' => $auction->id,
                'carrier_id' => $carrier->id,
                'context' => trim(($auction->auction_number ?? '') . ' ' . ($carrier->name ?? '')),
                'data' => [
                    'auction_number' => $auction->auction_number,
                    'auction_title' => $auction->title,
                    'lot_code' => 'DEMO',
                ],
            ]);
        }
    }
}

