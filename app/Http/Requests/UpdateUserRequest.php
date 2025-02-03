<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'user_email' => 'sometimes|email|unique:users,user_email,' . $this->route('user_id'),
            'user_fullname' => 'sometimes|required|string', 
            'user_password' => 'nullable|min:6',
            'user_nohp' => 'sometimes|nullable|string|unique:users,user_nohp,' . $this->route('user_id'),
        ];
    }

    public function messages()
    {
        return [
            'user_email.sometimes' => 'Email is required if provided',
            'user_email.email' => 'Email format is invalid',
            'user_email.unique' => 'Email has already been taken',
            'user_fullname.sometimes' => 'Fullname is required if provided',
            'user_password.min' => 'Password must be at least 6 characters',
            'user_nohp.unique' => 'Phone number has already been taken',
        ];
    }
}
