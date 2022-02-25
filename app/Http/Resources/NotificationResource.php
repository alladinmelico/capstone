<?php

namespace App\Http\Resources;

use Illuminate\Support\Arr;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id'            => $this->id,
            'notifiable_id' => $this->notifiable_id,
            'type'          => Arr::get($this->data, 'type'),
            'message'       => Arr::get($this->data, 'message'),
            'read_at'       => $this->read_at,
            'read'          => !!$this->read_at,
            'created_at'    => $this->created_at,
            'model'         => Arr::get($this->data, 'model'),
        ];
    }
}
