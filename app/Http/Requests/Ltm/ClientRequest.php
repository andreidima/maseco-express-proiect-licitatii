<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'cui' => 'required|string|max:50',
            'registration_number' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'payment_terms_days' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
