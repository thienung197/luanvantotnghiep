<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'phone' => 'required|numeric|unique:users,phone',
            'address' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'image' => 'required|image'
        ];
    }
}
