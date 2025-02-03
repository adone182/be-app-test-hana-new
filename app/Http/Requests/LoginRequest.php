<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
    public function rules()
    {
        return [
            'user_email' => 'required|email|exists:users,user_email',
            'user_password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'user_email.required' => 'Email diperlukan',
            'user_email.email' => 'Format email tidak valid',
            'user_email.exists' => 'Email tidak terdaftar',
            'user_password.required' => 'Password diperlukan',
            'user_password.min' => 'Password minimal terdiri dari 6 karakter',
        ];
    }
}
