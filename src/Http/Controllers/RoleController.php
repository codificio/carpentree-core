<?php

namespace Carpentree\Core\Http\Controllers;

use Carpentree\Core\Http\Resources\RoleResource;
use Carpentree\Core\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function list()
    {
        if (!Auth::user()->can('roles.read')) {
            throw UnauthorizedException::forPermissions(['roles.read']);
        }

        return response()->json(RoleResource::collection(Role::all()));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncWithUser($id, Request $request)
    {
        if (!Auth::user()->can('users.manage-roles')) {
            throw UnauthorizedException::forPermissions(['users.manage-roles']);
        }

        $request->validate([
            'roles' => 'required|array',
        ]);

        /** @var User $user */
        $user = User::findOrFail($id);
        $user->syncRoles($request->input('roles'));

        return response()->json([
            "status" => "success",
            "data" => null
        ]);
    }

    public function revokeFromUser($id, Request $request)
    {
        if (!Auth::user()->can('users.manage-roles')) {
            throw UnauthorizedException::forPermissions(['users.manage-roles']);
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $name = $request->input('name');

        /** @var User $user */
        $user = User::findOrFail($id);
        $user->removeRole($name);

        return response()->json([
            "status" => "success",
            "data" => null
        ]);
    }

}
