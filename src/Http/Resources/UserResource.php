<?php

namespace Carpentree\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Meta
            'meta' => MetaFieldResource::collection($this->resource->meta),

            // Roles
            'roles' => RoleResource::collection($this->resource->roles),

            // Permissions
            'permissions' => PermissionResource::collection($this->resource->getAllPermissions()),
        ];
    }
}
