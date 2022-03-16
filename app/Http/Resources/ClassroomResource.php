<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
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
            'description' => $this->description,
            'description_heading' => $this->description_heading,
            'google_classroom_id' => $this->google_classroom_id,
            'name' => $this->name,
            'section_id' => $this->section_id,
            'section' => $this->whenLoaded('section'),
            'invite_code' => $this->invite_code,
            'subject_id' => $this->subject_id,
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'subject_name' => $this->relationLoaded('subject') ? $this->subject->name : '',
            'users' => UserResource::collection($this->whenLoaded('users')),
            'schedules' => ScheduleResource::collection($this->whenLoaded('schedules')),
        ];
    }
}
