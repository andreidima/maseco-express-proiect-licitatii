<?php

namespace App\Observers\Ltm;

use App\Models\Ltm\Lot;
use App\Services\NotificationLogger;

class LotObserver
{
    public function __construct(private readonly NotificationLogger $logger)
    {
    }

    public function created(Lot $lot): void
    {
        $lot->loadMissing([
            'auction:id,auction_number,title',
        ]);

        $auctionLabel = $lot->auction?->auction_number ?: ($lot->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($lot->code ? "LOT {$lot->code}" : ''));

        $this->logger->admin('lot.created', [
            'lot_code' => $lot->code,
            'auction_number' => $lot->auction?->auction_number,
            'auction_title' => $lot->auction?->title,
        ], [
            'auction_id' => $lot->auction_id,
            'lot_id' => $lot->id,
        ], $context);
    }

    public function updated(Lot $lot): void
    {
        $lot->loadMissing([
            'auction:id,auction_number,title',
        ]);

        $meaningfulChanges = array_diff(array_keys($lot->getChanges()), ['updated_at']);
        if ($meaningfulChanges === []) {
            return;
        }

        $auctionLabel = $lot->auction?->auction_number ?: ($lot->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($lot->code ? "LOT {$lot->code}" : ''));

        $this->logger->admin('lot.updated', [
            'lot_code' => $lot->code,
            'auction_number' => $lot->auction?->auction_number,
            'auction_title' => $lot->auction?->title,
        ], [
            'auction_id' => $lot->auction_id,
            'lot_id' => $lot->id,
        ], $context);
    }

    public function deleted(Lot $lot): void
    {
        $lot->loadMissing([
            'auction:id,auction_number,title',
        ]);

        $auctionLabel = $lot->auction?->auction_number ?: ($lot->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($lot->code ? "LOT {$lot->code}" : ''));

        $this->logger->admin('lot.deleted', [
            'lot_code' => $lot->code,
            'auction_number' => $lot->auction?->auction_number,
            'auction_title' => $lot->auction?->title,
        ], [
            'auction_id' => $lot->auction_id,
            'lot_id' => $lot->id,
        ], $context);
    }
}

