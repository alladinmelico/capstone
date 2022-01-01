<?php

namespace App\Http\Requests;

use App\Enums\ScheduleRepeatType;
use App\Enums\ScheduleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'title' => 'nullable|string',
            'start_at' => 'required|date_format:H:i|before:end_at',
            'start_date' => 'required|date_format:Y-m-d|before:end_at',
            'end_at' => 'required|date_format:H:i|after:start_at',
            'end_date' => 'required|date_format:Y-m-d|after:start_date',
            'days_of_week' => 'sometimes',
            'is_recurring' => 'required|boolean',
            'type' => ['required', Rule::in(ScheduleType::getValues())],
            'repeat_by' => ['sometimes', Rule::in(ScheduleRepeatType::getValues())],
            'note' => 'nullable',
            'facility_id' => 'required|numeric|exists:facilities,id',
            'classroom_id' => 'required|numeric|exists:classrooms,id',
            'user_id' => 'required|numeric|exists:users,id',
        ];
    }
}
