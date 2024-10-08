<?php

namespace App\Http\Requests;

class AuthRequest extends StandardRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ];
    }
}
