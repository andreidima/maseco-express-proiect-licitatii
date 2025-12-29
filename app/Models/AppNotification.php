<?php

namespace App\Models;

use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Document;
use App\Models\Ltm\Lot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'audience',
        'carrier_id',
        'actor_user_id',
        'auction_id',
        'lot_id',
        'bid_id',
        'document_id',
        'type',
        'context',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    public function auction(): BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class, 'lot_id');
    }

    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class, 'bid_id');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(NotificationRead::class, 'notification_id');
    }

    public static function readerFor(?User $user): array
    {
        $isParticipant = ($user?->role ?? null) === 'Participant licitatii';

        if ($isParticipant) {
            return [
                'kind' => 'carrier',
                'id' => (int) ($user?->carrier_id ?? 0),
            ];
        }

        return [
            'kind' => 'user',
            'id' => (int) ($user?->id ?? 0),
        ];
    }

    public function scopeForCurrentUser(Builder $query, User $user): Builder
    {
        if (($user->role ?? null) === 'Participant licitatii') {
            return $query
                ->where('audience', 'carrier')
                ->where('carrier_id', $user->carrier_id);
        }

        return $query->where('audience', 'admin');
    }

    public function scopeWithReadState(Builder $query, string $readerKind, int $readerId): Builder
    {
        return $query
            ->withExists([
                'reads as is_read' => fn ($q) => $q
                    ->where('reader_kind', $readerKind)
                    ->where('reader_id', $readerId),
            ])
            ->withMax([
                'reads as read_at' => fn ($q) => $q
                    ->where('reader_kind', $readerKind)
                    ->where('reader_id', $readerId),
            ], 'read_at');
    }

    public function scopeUnreadFor(Builder $query, string $readerKind, int $readerId): Builder
    {
        return $query->whereDoesntHave('reads', fn ($q) => $q
            ->where('reader_kind', $readerKind)
            ->where('reader_id', $readerId));
    }

    public function markRead(string $readerKind, int $readerId): void
    {
        NotificationRead::query()->updateOrCreate(
            [
                'notification_id' => $this->id,
                'reader_kind' => $readerKind,
                'reader_id' => $readerId,
            ],
            [
                'read_at' => now(),
            ]
        );
    }
}

