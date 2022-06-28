<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthoritySetPricingRequest extends FormRequest
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
            'unit_amount' => 'required|numeric',
            'tax_rate' => 'integer|min:0|max:100',
            'is_taxed' => 'required|integer|min:0|max:1',
        ];
    }
}
