<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TemperatureResource extends JsonResource
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
            'temperature' => $this->temperature,
            'created_at' => $this->created_at,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user'),
        ];
    }
}
