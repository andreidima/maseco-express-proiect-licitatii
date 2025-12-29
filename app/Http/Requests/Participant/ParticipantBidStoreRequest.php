<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantBidStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lot_id' => 'required|exists:ltm_lots,id',
            'price_per_trip_eur' => 'required|numeric|min:0',
            'price_per_ton_eur' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'surcharge_fuel_percent' => 'nullable|numeric|min:0',
            'payment_terms_days' => 'nullable|integer|min:0',
            'internal_comment' => 'nullable|string',
        ];
    }
}
