<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'schedule_id' => $this->schedule_id,
            'user_id' => $this->user_id,
            'batch' => $this->batch,
            'is_absent' => $this->is_absent,
            'is_approved' => $this->is_approved,
            'note' => $this->note,
            'attachment' => $this->attachment,
            'user' => new UserResource($this->whenLoaded('user')),
            'schedule' => $this->whenLoaded('schedule'),
        ];
    }
}
