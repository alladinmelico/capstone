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
            'building_id' => ['required', Rule::in(array_keys(config('constants.buildings')))],
            'type' => ['required', Rule::in(array_keys(config('constants.facilities.types')))],
        ];
    }
}
