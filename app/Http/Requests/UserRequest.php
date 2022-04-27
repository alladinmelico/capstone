<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserType;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $roles = UserType::getValues();
        array_shift($roles);

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'year' => 'nullable|integer',
            'section_id' => 'nullable|numeric|exists:sections,id',
            'course_id' => 'nullable|numeric|exists:courses,id',
            'school_id' => 'nullable|string|max:12|regex:/(TUPT-)\d\d-\d\d\d\d/i|unique:users,school_id,'.optional($this->user)->id.',id',
            'verified_teacher' => 'nullable|boolean',
            'role_id' => ['required', Rule::in($roles)],
            'attachment' => 'sometimes|file|mimes:jpg,jpeg,bmp,png',
        ];
    }
}
