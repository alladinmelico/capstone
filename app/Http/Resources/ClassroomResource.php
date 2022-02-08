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
            'section' => $this->section,
            'invite_code' => $this->invite_code,
            'subject_id' => $this->subject_id,
            'subject' => new SubjectResource($this->subject),
            'subject_name' => $this->subject->name,
            'users' => UserResource::collection($this->users),
            'schedules' => ScheduleResource::collection($this->whenLoaded('schedules')),
        ];
    }
}
