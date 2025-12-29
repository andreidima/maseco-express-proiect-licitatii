<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class AuctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'auction_number' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:ltm_clients,id',
            'route_id' => 'required|exists:ltm_routes,id',
            'type' => 'required|string|max:100',
            'status' => 'required|string|max:100',
            'estimated_value_eur' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'total_lots' => 'nullable|integer|min:0',
            'expected_volume_tons' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
