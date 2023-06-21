<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreateClient extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:clients',
            'phonenumber' =>'required|min:10',
            'dateofbirth' => 'required|date|date_format:Y-m-d',
            'complement' => 'required|max:255',
            'neighborhood' => 'required|max:255',
            'zipcode' => 'required|min:9'
        ];
    }
}
