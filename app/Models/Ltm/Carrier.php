<?php

namespace App\Models\Ltm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends Model
{
    use HasFactory;

    protected $table = 'ltm_carriers';

    protected $fillable = [
        'name',
        'cui',
        'registration_number',
        'contact_person',
        'phone',
        'email',
        'city',
        'country',
        'rating',
        'notes',
    ];

    public function bids()
    {
        return $this->hasMany(Bid::class, 'carrier_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'carrier_id');
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class, 'carrier_id');
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class, 'carrier_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'carrier_id');
    }
}
