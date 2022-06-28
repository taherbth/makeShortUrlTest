<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditInstitutionProfileRequest extends FormRequest
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
            'avatar'=>'mimes:jpeg,bmp,png|max:2000',
            'institution_name'=>'required|string',
            'institution_address'=>'required|string',
            'established_at'=>'required|date',
        ];
    }
}
