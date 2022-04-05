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
            'start_date' => 'required|date_format:Y-m-d',
            'end_at' => 'required|date_format:H:i|after:start_at',
            'end_date' => 'nullable|date_format:Y-m-d',
            'days_of_week' => 'sometimes',
            'is_recurring' => 'required|boolean',
            'type' => ['required', Rule::in(ScheduleType::getValues())],
            'repeat_by' => ['nullable', Rule::in(ScheduleRepeatType::getValues())],
            'note' => 'nullable',
            'facility_id' => 'required|numeric|exists:facilities,id',
            'classroom_id' => 'nullable|numeric|exists:classrooms,id',
            'is_end_of_sem' => 'sometimes|boolean',
            'attachment' => 'sometimes|file|mimes:jpg,jpeg,bmp,png,pdf',
            'users' => 'nullable|array',
            'attachment_string' => 'nullable|string',
            'remarks' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->is_end_of_sem) {
            $this->merge([
                'end_date' => env('DATE_END_OF_SEM'),
            ]);
        }
    }
}
