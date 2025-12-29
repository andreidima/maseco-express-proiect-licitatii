<?php

namespace App\Models\Ltm;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'ltm_contracts';

    protected $fillable = [
        'auction_id',
        'carrier_id',
        'client_id',
        'contract_number',
        'contract_type',
        'total_value_eur',
        'average_price_per_trip_eur',
        'currency_id',
        'valid_from',
        'valid_to',
        'status',
        'notes',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'contract_id');
    }
}
