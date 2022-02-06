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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'year' => 'required|integer',
            'section' => 'required|string|max:255' ,
            'google_id' => 'nullable|string|max:255',
            'avatar' => 'nullable|string|max:255',
            'avatar_original' => 'nullable|string|max:255',
            'course_id' => 'required|numeric|exists:courses,id',
            'school_id' => 'required|string|max:255',
            'verified_teacher' => 'nullable|boolean'
        ];
    }
}
