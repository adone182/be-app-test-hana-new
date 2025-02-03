<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'user_email' => 'required|email|unique:users,user_email',
            'user_fullname' => 'required|string',
            'user_password' => 'required|min:6',
            'user_nohp' => 'nullable|string|unique:users,user_nohp',
        ];
    }

    public function messages()
    {
        return [
            'user_email.required' => 'Email is required',
            'user_email.email' => 'Email format is invalid',
            'user_email.unique' => 'Email has already been taken',
            'user_fullname.required' => 'Fullname is required',
            'user_password.required' => 'Password is required',
            'user_password.min' => 'Password must be at least 6 characters',
            'user_nohp.unique' => 'Phone number has already been taken',
        ];
    }
}
