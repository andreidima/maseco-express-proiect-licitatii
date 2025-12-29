<?php

namespace App\Models\Ltm;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $table = 'ltm_bids';

    protected $fillable = [
        'auction_id',
        'lot_id',
        'carrier_id',
        'price_per_trip_eur',
        'price_per_ton_eur',
        'currency_id',
        'surcharge_fuel_percent',
        'payment_terms_days',
        'status',
        'internal_comment',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class, 'lot_id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
