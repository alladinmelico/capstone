<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RfidRequest extends FormRequest
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
            'value' => 'required|string|unique:rfids,value,'.optional($this->rfid)->id.',id',
            'is_logged' => 'required|boolean',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
