<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => 'required|in:SuperAdmin,Admin,Operator,Participant licitatii',
            'name' => 'required|max:255',
            'telefon' => 'nullable|max:50',
            'email' => 'required|max:255|email:rfc,dns|unique:users,email,' . $this->route('user')?->id,
            'password' => ($this->isMethod('POST') ? 'required' : 'nullable') . '|min:8|max:255|confirmed',
            'activ' => 'required',
            'carrier_id' => 'nullable|exists:ltm_carriers,id|required_if:role,Participant licitatii',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => __('validation.custom.password.required'),
            'password.min' => __('validation.custom.password.min'),
            'password.max' => __('validation.custom.password.max'),
        ];
    }
}
