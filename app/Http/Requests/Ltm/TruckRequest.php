<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class TruckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'carrier_id' => 'required|exists:ltm_carriers,id',
            'plate_number' => 'required|string|max:20',
            'truck_type' => 'required|string|max:100',
            'max_weight_tons' => 'nullable|numeric|min:0',
            'euro_class' => 'nullable|string|max:50',
            'has_adr' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
        ];
    }
}
