<?php

namespace Carpentree\Core\Http\Resources;

use Carpentree\Core\Http\Resources\Relationships\CategoryResourceRelationship;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class CategoryResource extends JsonResource
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
            'locale' => App::getLocale(),

            'attributes' => [
                'type' => $this->type,
                'slug' => $this->slug,
                'name' => $this->name,
                'description' => $this->description,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ],

            'relationships' => [
                // Parent
                'parent' => $this->whenLoaded('parent', array(
                    'data' => CategoryResourceRelationship::make($this->parent)
                ), null),
            ]

        ];
    }

}
