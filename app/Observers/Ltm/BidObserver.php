<?php

namespace App\Observers\Ltm;

use App\Models\Ltm\Bid;
use App\Services\NotificationLogger;

class BidObserver
{
    public function __construct(private readonly NotificationLogger $logger)
    {
    }

    public function created(Bid $bid): void
    {
        $bid->loadMissing([
            'auction:id,auction_number,title',
            'lot:id,code,auction_id',
            'carrier:id,name',
        ]);

        $auctionLabel = $bid->auction?->auction_number ?: ($bid->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($bid->lot?->code ? "LOT {$bid->lot->code}" : '') . ' ' . ($bid->carrier?->name ?? ''));

        $this->logger->admin('bid.created', [
            'auction_number' => $bid->auction?->auction_number,
            'auction_title' => $bid->auction?->title,
            'lot_code' => $bid->lot?->code,
            'carrier_name' => $bid->carrier?->name,
        ], [
            'auction_id' => $bid->auction_id,
            'lot_id' => $bid->lot_id,
            'bid_id' => $bid->id,
            'carrier_id' => $bid->carrier_id,
        ], $context);
    }

    public function updated(Bid $bid): void
    {
        $bid->loadMissing([
            'auction:id,auction_number,title',
            'lot:id,code,auction_id',
            'carrier:id,name',
        ]);

        $auctionLabel = $bid->auction?->auction_number ?: ($bid->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($bid->lot?->code ? "LOT {$bid->lot->code}" : '') . ' ' . ($bid->carrier?->name ?? ''));

        if ($bid->wasChanged('status')) {
            $from = $bid->getOriginal('status');
            $to = $bid->status;

            $this->logger->admin('bid.status_changed', [
                'auction_number' => $bid->auction?->auction_number,
                'auction_title' => $bid->auction?->title,
                'lot_code' => $bid->lot?->code,
                'carrier_name' => $bid->carrier?->name,
                'from' => $from,
                'to' => $to,
            ], [
                'auction_id' => $bid->auction_id,
                'lot_id' => $bid->lot_id,
                'bid_id' => $bid->id,
                'carrier_id' => $bid->carrier_id,
            ], $context);

            if ((string) $to === 'acceptatŽŸ') {
                $this->logger->carrier((int) ($bid->carrier_id ?? 0), 'bid.status_accepted', [
                    'auction_number' => $bid->auction?->auction_number,
                    'auction_title' => $bid->auction?->title,
                    'lot_code' => $bid->lot?->code,
                ], [
                    'auction_id' => $bid->auction_id,
                    'lot_id' => $bid->lot_id,
                    'bid_id' => $bid->id,
                    'carrier_id' => $bid->carrier_id,
                ], $context);
            }

            if ((string) $to === 'respinsŽŸ') {
                $this->logger->carrier((int) ($bid->carrier_id ?? 0), 'bid.status_rejected', [
                    'auction_number' => $bid->auction?->auction_number,
                    'auction_title' => $bid->auction?->title,
                    'lot_code' => $bid->lot?->code,
                ], [
                    'auction_id' => $bid->auction_id,
                    'lot_id' => $bid->lot_id,
                    'bid_id' => $bid->id,
                    'carrier_id' => $bid->carrier_id,
                ], $context);
            }

            return;
        }

        $meaningfulFields = [
            'price_per_trip_eur',
            'price_per_ton_eur',
            'currency_id',
            'surcharge_fuel_percent',
            'payment_terms_days',
            'internal_comment',
        ];
        $changed = array_intersect(array_keys($bid->getChanges()), $meaningfulFields);
        if ($changed === []) {
            return;
        }

        $this->logger->admin('bid.updated', [
            'auction_number' => $bid->auction?->auction_number,
            'auction_title' => $bid->auction?->title,
            'lot_code' => $bid->lot?->code,
            'carrier_name' => $bid->carrier?->name,
        ], [
            'auction_id' => $bid->auction_id,
            'lot_id' => $bid->lot_id,
            'bid_id' => $bid->id,
            'carrier_id' => $bid->carrier_id,
        ], $context);
    }

    public function deleted(Bid $bid): void
    {
        $bid->loadMissing([
            'auction:id,auction_number,title',
            'lot:id,code,auction_id',
            'carrier:id,name',
        ]);

        $auctionLabel = $bid->auction?->auction_number ?: ($bid->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($bid->lot?->code ? "LOT {$bid->lot->code}" : '') . ' ' . ($bid->carrier?->name ?? ''));

        $this->logger->admin('bid.deleted', [
            'auction_number' => $bid->auction?->auction_number,
            'auction_title' => $bid->auction?->title,
            'lot_code' => $bid->lot?->code,
            'carrier_name' => $bid->carrier?->name,
        ], [
            'auction_id' => $bid->auction_id,
            'lot_id' => $bid->lot_id,
            'bid_id' => $bid->id,
            'carrier_id' => $bid->carrier_id,
        ], $context);
    }
}

