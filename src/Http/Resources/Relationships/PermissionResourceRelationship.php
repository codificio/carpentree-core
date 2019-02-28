<?php

namespace Carpentree\Core\Http\Resources\Relationships;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResourceRelationship extends JsonResource
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
            'type' => 'permissions',
            'id' => $this->id,

            'attributes' => [
                'name' => $this->name
            ]
        ];
    }
}
