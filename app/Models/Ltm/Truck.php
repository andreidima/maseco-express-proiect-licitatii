<?php

namespace App\Models\Ltm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $table = 'ltm_trucks';

    protected $fillable = [
        'carrier_id',
        'plate_number',
        'truck_type',
        'max_weight_tons',
        'euro_class',
        'has_adr',
        'notes',
    ];

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }
}
