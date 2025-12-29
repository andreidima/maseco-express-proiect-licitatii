<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class RouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:100',
            'origin_city' => 'required|string|max:100',
            'origin_country' => 'required|string|max:100',
            'destination_city' => 'required|string|max:100',
            'destination_country' => 'required|string|max:100',
            'distance_km' => 'nullable|integer|min:0',
            'typical_goods' => 'nullable|string|max:255',
            'average_weight_tons' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
