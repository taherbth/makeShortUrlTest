<?php

namespace App\Http\Requests;

use App\Models\Facilitator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VideoInfoUploadRequest extends FormRequest
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
            'title'=>'required|min:5',
            'details'=>'required|min:5',
            'thumbnail'=>'required',
            'topic'=>'required',
            'chapter'=>'required|numeric',
            'episode'=>'required|numeric',
            'is_public'=>'boolean',
            'is_draft'=>'boolean',

            'questions'=>'required|array',
            'questions.*'=>'string',

            'question_type'=>'required|array',
            'question_type.*'=>'required_with:questions|integer|between:1,5',

            'answer_placeholder.*'=>'required_with:question_type|required_if:question_type,1|nullable',
            'answer_length.*'=>'required_with:question_type|required_if:question_type,1|nullable',

            'answer_placeholder.*'=>'required_with:question_type|required_if:question_type,2|nullable',
            'answer_min_length.*'=>'required_with:question_type|required_if:question_type,2|nullable',
            'answer_max_length.*'=>'required_with:question_type|required_if:question_type,2|nullable',

            'total_option.*'=>'required_with:question_type|required_if:question_type,3|nullable',
            'answer_option.*'=>'required_with:question_type|required_if:question_type,3|array|nullable',
            'answer_option.*.*'=>'string',

            'total_option.*'=>'required_with:question_type|required_if:question_type,4|nullable',
            'answer_option.*'=>'required_with:question_type|required_if:question_type,4|array|nullable',
            'answer_option.*.*'=>'string',

            'total_option.*'=>'required_with:question_type|required_if:question_type,5|nullable',
            'answer_option.*'=>'required_with:question_type|required_if:question_type,5|array|nullable',
            'answer_option.*.*'=>'string',
        ];
    }

    public function messages()
    {
        $messages = [];

        if ($this->get('questions')) {
            foreach ($this->get('questions') as $key => $value) {
                $messages["questions.$key.string"] = "Question title of question $key field is required.";
            }
        }

        if ($this->get('question_type')) {
            foreach ($this->get('question_type') as $key => $value) {
                $messages["question_type.$key.required_with"] = $messages["question_type.$key.string"] = "Answer type of question $key field is required.";
            }
        }

        if ($this->get('answer_placeholder')) {
            foreach ($this->get('answer_placeholder') as $key => $value) {
                $messages["answer_placeholder.$key.required_with"] = "Placeholder text of question $key field is required.";
            }
        }

        if ($this->get('answer_length')) {
            foreach ($this->get('answer_length') as $key => $value) {
                $messages["answer_length.$key.required_with"] = "Character limit of question $key field is required.";
            }
        }

        if ($this->get('answer_min_length')) {
            foreach ($this->get('answer_min_length') as $key => $value) {
                $messages["answer_min_length.$key.required_with"] = "Minimum number range of question $key field is required.";
            }
        }

        if ($this->get('answer_max_length')) {
            foreach ($this->get('answer_max_length') as $key => $value) {
                $messages["answer_max_length.$key.required_with"] = "Maximum number range of question $key field is required.";
            }
        }

        if ($this->get('total_option')) {
            foreach ($this->get('total_option') as $key => $value) {
                $messages["total_option.$key.required_with"] = "Number of option of question $key field is required.";
            }
        }

        if ($this->get('answer_option')) {
            foreach ($this->get('answer_option') as $opt_key => $opt_value) {
                foreach ($opt_value as $key => $value) {
                    //$messages["answer_option.$opt_key.$key.string"] = "Answer option $key of question $opt_key field is required.";
                }
            }
        }

        return $messages;
    }
}
