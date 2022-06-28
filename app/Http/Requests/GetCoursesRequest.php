<?php

namespace App\Http\Requests;

use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GetCoursesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        if(Facilitator::find(Facilitator::return_id())->status == 1 || Student::find(Facilitator::return_id())->status == 1 || Institution::find(Institution::return_id())->status == 1)
//            return true;
//        else return false;
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
            'topic'=>'sometimes|nullable|string',
            'chapter'=>'sometimes|numeric|nullable',
            'episode'=>'sometimes|numeric|nullable',
            'query'=>'sometimes|string|nullable',
        ];
    }
}
