<?php

namespace App\Http\Requests\Users;

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
    public function rules(): array
    {
        return [
            'name' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'phone' => 'required|numeric|unique:users,phone,' . $this->user,
            'address' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user,
            'password' => 'nullable|min:8',
            'image' => 'nullable|image'
        ];
    }
}
