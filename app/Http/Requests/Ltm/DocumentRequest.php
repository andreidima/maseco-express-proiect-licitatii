<?php

namespace App\Http\Requests\Ltm;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contract_id' => 'nullable|exists:ltm_contracts,id',
            'auction_id' => 'nullable|exists:ltm_auctions,id',
            'client_id' => 'nullable|exists:ltm_clients,id',
            'carrier_id' => 'nullable|exists:ltm_carriers,id',
            'type' => 'required|string|max:150',
            'file_path' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}
