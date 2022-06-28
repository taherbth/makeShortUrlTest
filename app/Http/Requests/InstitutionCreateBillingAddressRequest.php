<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionCreateBillingAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country' => 'required|string',
            'zip_code' => 'required|numeric',
            'state' => 'required|string',
            'city' => 'required|string',
            'address_line_1' => 'required|string',
            'address_line_2' => 'required|string',
            'email' => 'required|email',
            'is_default' => 'integer|min:0|max:1',
        ];
    }
}
