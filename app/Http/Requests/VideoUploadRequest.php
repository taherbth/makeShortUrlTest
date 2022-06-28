<?php

namespace App\Http\Requests;

use App\Models\Facilitator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VideoUploadRequest extends FormRequest
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
//            'video' => 'required|file|mimetypes:video/mp4,video/mpeg,video/x-matroska,video/m4v|max:200000',
            'video' => 'required|file|max:1000000',
        ];
    }
}
