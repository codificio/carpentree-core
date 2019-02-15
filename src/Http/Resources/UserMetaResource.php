<?php

namespace Carpentree\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMetaResource extends JsonResource
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
            'user_id' => $this->user_id,
            'key' => $this->key,
            'value' => $this->value
        ];
    }
}
