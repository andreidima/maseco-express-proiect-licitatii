<?php

namespace App\Observers\Ltm;

use App\Models\Ltm\Auction;
use App\Services\NotificationLogger;

class AuctionObserver
{
    public function __construct(private readonly NotificationLogger $logger)
    {
    }

    public function created(Auction $auction): void
    {
        $context = trim(($auction->auction_number ?? '') . ' ' . ($auction->title ?? ''));

        $this->logger->admin('auction.created', [
            'auction_number' => $auction->auction_number,
            'auction_title' => $auction->title,
        ], [
            'auction_id' => $auction->id,
        ], $context);
    }

    public function updated(Auction $auction): void
    {
        $context = trim(($auction->auction_number ?? '') . ' ' . ($auction->title ?? ''));

        if ($auction->wasChanged('status')) {
            $this->logger->admin('auction.status_changed', [
                'auction_number' => $auction->auction_number,
                'auction_title' => $auction->title,
                'from' => $auction->getOriginal('status'),
                'to' => $auction->status,
            ], [
                'auction_id' => $auction->id,
            ], $context);

            return;
        }

        $meaningfulChanges = array_diff(array_keys($auction->getChanges()), ['updated_at']);
        if ($meaningfulChanges === []) {
            return;
        }

        $this->logger->admin('auction.updated', [
            'auction_number' => $auction->auction_number,
            'auction_title' => $auction->title,
        ], [
            'auction_id' => $auction->id,
        ], $context);
    }

    public function deleted(Auction $auction): void
    {
        $context = trim(($auction->auction_number ?? '') . ' ' . ($auction->title ?? ''));

        $this->logger->admin('auction.deleted', [
            'auction_number' => $auction->auction_number,
            'auction_title' => $auction->title,
        ], [
            'auction_id' => $auction->id,
        ], $context);
    }
}

