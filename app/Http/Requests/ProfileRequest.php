<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ProfileRequest extends FormRequest
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
        Log::alert($this);
        return [
            'name' => 'required|string|min:3',
            'school_id' => 'required|string|max:12|regex:/(TUPT-)\d\d-\d\d\d\d/i|unique:users,school_id,'.auth()->user()->id.',id',
            'course_id' => 'sometimes|numeric|exists:courses,id',
            'year' => 'sometimes|numeric|between:1,5',
            'section_id' => 'sometimes|numeric|exists:sections,id',
            'attachment' => 'sometimes|file|mimes:jpg,jpeg,bmp,png',
        ];
    }
}
