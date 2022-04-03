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
            'department_id' => $this->department_id,
            'svg_key' => $this->svg_key,
            'department' => config('constants.departments.' . $this->department_id),
            'building' => config('constants.buildings.' . $this->building_id),
            'schedules' => $this->whenLoaded('schedules'),
            'cover' => $this->cover,
            'deleted_at' => $this->deleted_at,
            'staff_id' => $this->staff_id
        ];
    }
}
