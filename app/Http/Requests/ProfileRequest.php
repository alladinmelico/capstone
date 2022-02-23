<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name' => 'sometimes|string|min:3',
            'email' => 'sometimes|email|ends_with:@tup.edu.ph',
            'school_id' => 'sometimes|string|max:12|regex:/(TUPT-)\d\d-\d\d\d\d/i',
            'course_id' => 'sometimes|numeric|exists:courses,id',
            'year' => 'sometimes|numeric|between:1,4',
            // 'section' => 'sometimes|numeric|exists:sections,id',
        ];
    }
}
