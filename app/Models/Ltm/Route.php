<?php

namespace App\Models\Ltm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $table = 'ltm_routes';

    protected $fillable = [
        'code',
        'origin_city',
        'origin_country',
        'destination_city',
        'destination_country',
        'distance_km',
        'typical_goods',
        'average_weight_tons',
        'notes',
    ];

    public function auctions()
    {
        return $this->hasMany(Auction::class, 'route_id');
    }
}
