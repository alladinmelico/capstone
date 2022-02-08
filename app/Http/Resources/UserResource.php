<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'year' => $this->year,
            'section' => $this->section ,
            'google_id' => $this->google_id,
            'avatar' => $this->avatar,
            'avatar_original' => $this->avatar_original,
            'role_id' => $this->role_id,
            'course_id' => $this->course_id,
            'course_name' => $this->relationLoaded('course') ? $this->course?->name : '',
            'school_id' => $this->school_id,
            'verified_teacher' => $this->verified_teacher,
        ];
    }
}
