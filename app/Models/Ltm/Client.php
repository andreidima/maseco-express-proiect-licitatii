<?php

namespace App\Models\Ltm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'ltm_clients';

    protected $fillable = [
        'name',
        'cui',
        'registration_number',
        'contact_person',
        'phone',
        'email',
        'city',
        'country',
        'payment_terms_days',
        'notes',
    ];

    public function auctions()
    {
        return $this->hasMany(Auction::class, 'client_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'client_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'client_id');
    }
}
