<?php

namespace App\Models\Ltm;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $table = 'ltm_auctions';

    protected $fillable = [
        'auction_number',
        'title',
        'description',
        'client_id',
        'route_id',
        'type',
        'status',
        'estimated_value_eur',
        'currency_id',
        'total_lots',
        'expected_volume_tons',
        'notes',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function lots()
    {
        return $this->hasMany(Lot::class, 'auction_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'auction_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'auction_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'auction_id');
    }
}
