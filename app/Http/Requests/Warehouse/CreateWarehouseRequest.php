<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class CreateWarehouseRequest extends FormRequest
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
            'capacity' => 'required|numeric',
            'size' => 'required|numeric',
            'isRefrigerated' => 'required|numeric',
            'location_id' => 'required',
            'street_address' => 'nullable',
            'ward' => 'nullable',
            'district' => 'nullable',
            'province' => 'nullable',
            'longitude' => 'nullable',
            'latitude' => 'nullable'
        ];
    }
}
