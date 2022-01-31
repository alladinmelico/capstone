<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'president_name' => $this->president_name,
            'faculty_name' => $this->faculty_name,
            'president' => $this->president,
            'faculty' => $this->faculty,
        ];
    }
}
