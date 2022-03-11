<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ScheduleRepeatType;
use App\Enums\ScheduleType;
use Illuminate\Validation\Rule;

class ScheduleUpdateRequest extends FormRequest
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
            'start_at' => 'sometimes|date_format:H:i|before:end_at',
            'start_date' => 'sometimes|date_format:Y-m-d|before:end_date',
            'end_at' => 'sometimes|date_format:H:i|after:start_at',
            'end_date' => 'sometimes|date_format:Y-m-d|after:start_date',
            'days_of_week' => 'sometimes',
            'is_recurring' => 'sometimes|boolean',
            'type' => ['sometimes', Rule::in(ScheduleType::getValues())],
            'repeat_by' => ['sometimes', Rule::in(ScheduleRepeatType::getValues())],
            'note' => 'nullable',
            'facility_id' => 'sometimes|numeric|exists:facilities,id',
            'classroom_id' => 'sometimes|numeric|exists:classrooms,id',
            'is_end_of_sem' => 'sometimes|boolean',
            'attachment' => 'sometimes|file|mimes:jpg,jpeg,bmp,png,pdf',
            'users' => 'nullable|array',
            'attachment_string' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->is_end_of_sem) {
            $this->merge([
                'end_date' => date("Y-m-d", mktime(11, 14, 54, 8, 12, 2023)),
            ]);
        }
    }
}
