<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Resources\PermissionResource;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list()
    {
        if (!Auth::user()->can('permissions.read')) {
            throw UnauthorizedException::forPermissions(['permissions.read']);
        }

        return PermissionResource::collection(Permission::all());
    }

}
