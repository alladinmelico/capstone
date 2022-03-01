<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
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
            'name' => 'nullable|string',
            'description_heading' => 'nullable|string',
            'description' => 'nullable|string',
            'google_classroom_id' => 'nullable|string',
            'subject_id' => 'nullable|numeric|exists:subjects,id',
            'section_id' => 'nullable|numeric|exists:sections,id',
            'users' => 'nullable|array',
        ];
    }
}
