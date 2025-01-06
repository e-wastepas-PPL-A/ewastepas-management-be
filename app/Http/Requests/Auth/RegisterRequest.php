<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'nullable|string|max:50',
            'email' => 'required|email|max:50|unique:management,email',
            'password' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:15|unique:management,phone',
            'photo' => 'nullable|string|max:255',
            'is_verified' => 'nullable|boolean',
            'is_admin' => 'nullable|boolean',
            'otp_code' => 'nullable|string|max:6',
        ];
    }
}
