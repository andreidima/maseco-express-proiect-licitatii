<?php

namespace App\Http\Requests\Participant;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'name' => 'required|max:255',
            'telefon' => 'nullable|max:50',
            'email' => 'required|max:255|email:rfc,dns|unique:users,email,' . $userId,
            'password' => 'nullable|min:8|max:255|confirmed',
        ];
    }
}

