<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class BidRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'auction_id' => 'required|exists:ltm_auctions,id',
            'lot_id' => 'required|exists:ltm_lots,id',
            'carrier_id' => 'required|exists:ltm_carriers,id',
            'price_per_trip_eur' => 'required|numeric|min:0',
            'price_per_ton_eur' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'surcharge_fuel_percent' => 'nullable|numeric|min:0',
            'payment_terms_days' => 'nullable|integer|min:0',
            'status' => 'required|string|max:100',
            'internal_comment' => 'nullable|string',
        ];
    }
}
