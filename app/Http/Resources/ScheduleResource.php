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
        $approver = null;

        if ($this->relationLoaded('facility') && $this->relationLoaded('classroom')) {
            if ($this->facility && $this->facility?->type !== 'Classroom') {
                $approver = $this->facility?->staff_id;
            } else {
                $approver = $this->classroom?->section?->faculty_id;
            }
        }

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
            'facility_id' => $this->facility_id,
            'batches' => BatchResource::collection($this->whenLoaded('batches')),
            'facility' => $this->whenLoaded('facility'),
            'user' => $this->whenLoaded('user'),
            'attachment' => $this->attachment,
            'batches_count' => $this->batches_count,
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'approver' => $approver,
            'approved_at' => $this->approved_at,
            'remarks' => $this->remarks
        ];
    }
}
