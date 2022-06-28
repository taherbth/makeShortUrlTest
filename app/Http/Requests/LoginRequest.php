<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email'=>'required|email',
            'password'=>'required',
            //'password'=>'required|min:8|regex:/^.*(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=\D*\d).{8,}.*$/',
            'remember_me'=>'string|min:4|max:5'
        ];
    }
}
