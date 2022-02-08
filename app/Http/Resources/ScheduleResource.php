<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'title' => $this->title,
            'start_at' => $this->start_at,
            'start_date' => $this->start_date,
            'end_at' => $this->end_at,
            'end_date' => $this->end_date,
            'days_of_week' => $this->days_of_week,
            'is_recurring' => $this->is_recurring,
            'type' => $this->type,
            'repeat_by' => $this->repeat_by,
            'user_id' => $this->user_id,
            'note' => $this->note,
            'classroom_id' => $this->classroom_id,
            'users' => $this->relationLoaded('classroom') ? $this->classroom?->users : null,
            'facility' => $this->facility,
            'attachment' => $this->attachment,
            'is_valid_now' => $this->is_valid,
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
        ];
    }
}
