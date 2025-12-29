<?php

namespace App\Observers\Ltm;

use App\Models\Ltm\Document;
use App\Services\NotificationLogger;

class DocumentObserver
{
    public function __construct(private readonly NotificationLogger $logger)
    {
    }

    public function created(Document $document): void
    {
        $document->loadMissing([
            'auction:id,auction_number,title',
        ]);

        $auctionLabel = $document->auction?->auction_number ?: ($document->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($document->type ?? ''));

        $this->logger->admin('document.created', [
            'type' => $document->type,
            'auction_number' => $document->auction?->auction_number,
            'auction_title' => $document->auction?->title,
        ], [
            'auction_id' => $document->auction_id,
            'document_id' => $document->id,
        ], $context);
    }

    public function updated(Document $document): void
    {
        $document->loadMissing([
            'auction:id,auction_number,title',
        ]);

        $meaningfulChanges = array_diff(array_keys($document->getChanges()), ['updated_at']);
        if ($meaningfulChanges === []) {
            return;
        }

        $auctionLabel = $document->auction?->auction_number ?: ($document->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($document->type ?? ''));

        $this->logger->admin('document.updated', [
            'type' => $document->type,
            'auction_number' => $document->auction?->auction_number,
            'auction_title' => $document->auction?->title,
        ], [
            'auction_id' => $document->auction_id,
            'document_id' => $document->id,
        ], $context);
    }

    public function deleted(Document $document): void
    {
        $document->loadMissing([
            'auction:id,auction_number,title',
        ]);

        $auctionLabel = $document->auction?->auction_number ?: ($document->auction?->title ?: '');
        $context = trim(($auctionLabel ? "AUC {$auctionLabel}" : '') . ' ' . ($document->type ?? ''));

        $this->logger->admin('document.deleted', [
            'type' => $document->type,
            'auction_number' => $document->auction?->auction_number,
            'auction_title' => $document->auction?->title,
        ], [
            'auction_id' => $document->auction_id,
            'document_id' => $document->id,
        ], $context);
    }
}

