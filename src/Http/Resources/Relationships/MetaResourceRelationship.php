<?php

namespace Carpentree\Core\Http\Resources\Relationships;

use Illuminate\Http\Resources\Json\JsonResource;

class MetaResourceRelationship extends JsonResource
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
            'type' => 'meta',
            'id' => $this->id,
            'attributes' => [
                'key' => $this->key,
                'value' => $this->value
            ]
        ];
    }
}
