<?php

namespace Carpentree\Core\Http\Resources\Relationships;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResourceRelationship extends JsonResource
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
            'type' => 'categories',
            'id' => $this->id,

            'attributes' => [
                'type' => $this->type,
                'slug' => $this->slug,
                'name' => $this->name,
                'description' => $this->description,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ]

        ];
    }

}
