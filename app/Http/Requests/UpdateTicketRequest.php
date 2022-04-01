<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserType;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Enums\TicketPriority;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
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
            'category' => ['sometimes', Rule::in(TicketType::getValues())],
            'priority' => ['sometimes', Rule::in(TicketPriority::getValues())],
            'status' => ['sometimes', Rule::in(TicketStatus::getValues())],
        ];
    }
}
