<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
            'section' => 'nullable|string',
            'start_at' => 'required|date_format:H:i|before:end_at',
            'end_at' => 'required|date_format:H:i|after:start_at',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'valid_until' => 'required|date',
            'note' => 'nullable',
            'facility_id' => 'required|numeric|exists:facilities,id',
            'user_id' => 'required|numeric|exists:users,id',
            'google_classroom_id' => 'nullable|string',
            'subject_id' => 'nullable|numeric|exists:subjects,id',
            'users' => 'nullable|array',
        ];
    }
}