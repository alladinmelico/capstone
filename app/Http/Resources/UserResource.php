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
            'section' => $this->whenLoaded('section'),
            'section_id' => $this->section_id,
            'google_id' => $this->google_id,
            'avatar' => $this->avatar,
            'avatar_original' => $this->avatar_original,
            'role_id' => $this->role_id,
            'course_id' => $this->course_id,
            'changes_verified' => $this->changes_verified,
            'course_name' => $this->relationLoaded('course') ? $this->course?->name : '',
            'course' => $this->whenLoaded('course'),
            'school_id' => $this->school_id,
            'verified_teacher' => $this->verified_teacher,
        ];
    }
}
