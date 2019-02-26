<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list()
    {
        if (!Auth::user()->can('roles.read')) {
            throw UnauthorizedException::forPermissions(['roles.read']);
        }

        return RoleResource::collection(Role::all());
    }

}
