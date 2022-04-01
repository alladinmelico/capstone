<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'title' => $this->title,
            'ticket_id' => $this->ticket_id,
            'priority' => $this->priority,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'status' => $this->status,
            'category' => $this->category,
            'user' => $this->whenLoaded('user'),
        ];
    }
}
