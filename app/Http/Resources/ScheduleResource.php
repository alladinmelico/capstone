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
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'day' => $this->day,
            'valid_until' => $validUntil,
            'readable_valid_until' => $validUntil->diffForHumans(),
            'note' => $this->note,
            'facility_id' => $this->facility_id,
            'facility_name' => $this->facility->name,
            'is_valid_now' => $isValid,
        ];
    }
}