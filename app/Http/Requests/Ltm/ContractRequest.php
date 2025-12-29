<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'auction_id' => 'required|exists:ltm_auctions,id',
            'carrier_id' => 'required|exists:ltm_carriers,id',
            'client_id' => 'required|exists:ltm_clients,id',
            'contract_number' => 'required|string|max:150',
            'contract_type' => 'required|string|max:100',
            'total_value_eur' => 'required|numeric|min:0',
            'average_price_per_trip_eur' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
            'status' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
