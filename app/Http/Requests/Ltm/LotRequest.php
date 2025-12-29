<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class LotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'auction_id' => 'required|exists:ltm_auctions,id',
            'code' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'goods_type' => 'required|string|max:150',
            'weight_tons' => 'nullable|numeric|min:0',
            'pallets' => 'nullable|integer|min:0',
            'trips_per_month' => 'nullable|integer|min:0',
            'max_budget_eur' => 'nullable|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'pickup_city' => 'nullable|string|max:100',
            'pickup_country' => 'nullable|string|max:100',
            'delivery_city' => 'nullable|string|max:100',
            'delivery_country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ];
    }
}
