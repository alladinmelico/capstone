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
        $now = Carbon::now()->setTimezone(config('app.timezone'));
        $validUntil = Carbon::parse($this->valid_until);
        $time = $now->format('H:i:s');

        $isValid = true;

        if (($now->greaterThanOrEqualTo($validUntil)) ||
            ($this->day !== strtolower($now->englishDayOfWeek)) ||
            (time() >= strtotime($this->end_at)) ||
            (time() <= strtotime($this->start_at))) {
            $isValid = false;
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
            'created_by' => $this->user_id,
            'note' => $this->note,
            'facility_id' => $this->facility_id,
            'facility_name' => $this->facility->name,
            'readable_valid_until' => $validUntil->diffForHumans(),
            'is_valid_now' => $isValid,
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
        ];
    }
}
