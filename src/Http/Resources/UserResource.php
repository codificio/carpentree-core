<?php

namespace Carpentree\Core\Http\Resources;

use Carpentree\Core\Http\Resources\Relationships\MetaResourceRelationship;
use Carpentree\Core\Http\Resources\Relationships\PermissionResourceRelationship;
use Carpentree\Core\Http\Resources\Relationships\RoleResourceRelationship;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * Class UserResource.
 *
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

                // Permissions
                'permissions' => $this->whenLoaded('roles', array(
                    'data' => PermissionResourceRelationship::collection($this->getAllPermissions())
                ), array()),

                // Roles
                'roles' => $this->whenLoaded('roles', array(
                    'data' => RoleResourceRelationship::collection($this->roles)
                ), array()),

                // Addresses
                'addresses' => $this->whenLoaded('addresses', array(
                    'data' => AddressResource::collection($this->addresses)
                ), array()),

                // Meta
                'meta' => $this->whenLoaded('meta', array(
                    'data' => MetaResourceRelationship::collection($this->meta)
                ), array())
            ]

        ];
    }

}
