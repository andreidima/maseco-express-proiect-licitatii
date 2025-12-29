<?php

namespace App\Models\Ltm;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $table = 'ltm_lots';

    protected $fillable = [
        'auction_id',
        'code',
        'description',
        'goods_type',
        'weight_tons',
        'pallets',
        'trips_per_month',
        'max_budget_eur',
        'currency_id',
        'pickup_city',
        'pickup_country',
        'delivery_city',
        'delivery_country',
        'notes',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'lot_id');
    }
}
