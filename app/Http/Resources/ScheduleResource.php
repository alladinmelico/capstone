<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;


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
        $now = Carbon::now();
        $validUntil = Carbon::parse($this->valid_until);
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
            'is_valid_now' => $now->lessThan($validUntil),
        ];
    }
}
