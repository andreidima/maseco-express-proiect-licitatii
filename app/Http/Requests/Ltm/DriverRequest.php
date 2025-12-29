<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'carrier_id' => 'required|exists:ltm_carriers,id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'languages' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'has_adr' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
        ];
    }
}
