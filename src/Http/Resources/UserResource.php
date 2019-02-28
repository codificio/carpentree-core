<?php

namespace Carpentree\Core\Http\Resources;

use Carpentree\Core\Http\Resources\Relationships\MetaResourceRelationship;
use Carpentree\Core\Http\Resources\Relationships\PermissionResourceRelationship;
use Carpentree\Core\Http\Resources\Relationships\RoleResourceRelationship;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * Class UserResource. JSON:API compliant.
 *
 * @see https://jsonapi.org
 * @package Carpentree\Core\Http\Resources
 */
class UserResource extends JsonResource
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
            // User fields
            'type' => 'users',
            'id' => $this->id,
            'locale' => App::getLocale(),

            'attributes' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'full_name' => $this->full_name,
                'email' => $this->email,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],

            'relationships' => [

                'permissions' => [
                    'data' => PermissionResourceRelationship::collection($this->resource->getAllPermissions())
                ],

                'roles' => [
                    'data' => RoleResourceRelationship::collection($this->roles)
                ],

                'meta' => [
                    'data' => MetaResourceRelationship::collection($this->meta)
                ]
            ]

        ];
    }

}
