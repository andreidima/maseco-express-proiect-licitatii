<?php

namespace App\Services;

use App\Models\AppNotification;
use Illuminate\Support\Facades\Auth;

class NotificationLogger
{
    public function admin(string $type, array $data = [], array $refs = [], ?string $context = null): AppNotification
    {
        return $this->log('admin', null, $type, $data, $refs, $context);
    }

    public function carrier(int $carrierId, string $type, array $data = [], array $refs = [], ?string $context = null): AppNotification
    {
        return $this->log('carrier', $carrierId, $type, $data, $refs, $context);
    }

    private function log(
        string $audience,
        ?int $carrierId,
        string $type,
        array $data,
        array $refs,
        ?string $context
    ): AppNotification {
        return AppNotification::create([
            'audience' => $audience,
            'carrier_id' => $carrierId ?? ($refs['carrier_id'] ?? null),
            'actor_user_id' => Auth::id(),
            'auction_id' => $refs['auction_id'] ?? null,
            'lot_id' => $refs['lot_id'] ?? null,
            'bid_id' => $refs['bid_id'] ?? null,
            'document_id' => $refs['document_id'] ?? null,
            'type' => $type,
            'context' => $context,
            'data' => $data,
        ]);
    }
}
