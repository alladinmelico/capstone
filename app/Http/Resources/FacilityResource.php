<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
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
            'capacity' => $this->capacity,
            'occupied' => $this->occupied,
            'type' => $this->type,
            'building_id' => $this->building_id,
            'building' => config('constants.buildings.' . $this->building_id),
            'schedules' => $this->whenLoaded('schedules'),
        ];
    }
}
