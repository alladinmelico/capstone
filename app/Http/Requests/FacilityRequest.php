<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $role = auth()->user()->role_id;
        return $role === UserType::ADMIN || $role === UserType::FACULTY;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'code' => 'required|string|unique:facilities,code,'.optional($this->facility)->id.',id',
            'capacity' => 'required|integer',
            'svg_key' => 'required|string|unique:facilities,svg_key,'.optional($this->facility)->id.',id',
            'building_id' => ['required', Rule::in(array_keys(config('constants.buildings')))],
            'department_id' => ['required', Rule::in(array_keys(config('constants.departments')))],
            'type' => ['required', Rule::in(array_keys(config('constants.facilities.types')))],
            'cover' => 'sometimes|file|mimes:jpg,jpeg,bmp,png',
            'staff_id' => 'sometimes|exists:users,id'
        ];
    }
}
