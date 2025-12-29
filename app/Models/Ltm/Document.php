<?php

namespace App\Models\Ltm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'ltm_documents';

    protected $fillable = [
        'contract_id',
        'auction_id',
        'client_id',
        'carrier_id',
        'type',
        'file_path',
        'description',
        'notes',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }
}
