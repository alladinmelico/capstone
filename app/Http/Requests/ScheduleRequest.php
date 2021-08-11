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
            'start_at' => 'required',
            'end_at' => 'required',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'valid_until' => 'required|date',
            'note' => 'nullable',
            'facility_id' => 'required|numeric|exists:facilities,id',
            'user_id' => 'required|numeric|exists:users,id',
        ];
    }
}