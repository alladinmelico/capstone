<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'department_id' => $this->department_id,
            'cover' => $this->cover,
            'users_count' => $this->users_count,
            'deleted_at' => $this->deleted_at,
            'department' => config('constants.departments.' . $this->department_id),
        ];
    }
}
