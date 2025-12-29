<?php

namespace App\Models\Ltm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'ltm_drivers';

    protected $fillable = [
        'carrier_id',
        'name',
        'phone',
        'email',
        'languages',
        'experience_years',
        'has_adr',
        'notes',
    ];

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }
}
