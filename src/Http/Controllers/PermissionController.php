<?php

namespace Carpentree\Core\Http\Controllers;

use Carpentree\Core\Http\Resources\PermissionResource;
use Carpentree\Core\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function list()
    {
        if (!Auth::user()->can('permissions.read')) {
            throw UnauthorizedException::forPermissions(['permissions.read']);
        }

        return response()->json(PermissionResource::collection(Permission::all()));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function giveToUser(Request $request)
    {
        if (!Auth::user()->can('users.manage-permissions')) {
            throw UnauthorizedException::forPermissions(['users.manage-permissions']);
        }

        $request->validate([
            'user_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        $name = $request->input('name');

        /** @var User $user */
        $user = User::findOrFail($request->input('user_id'));
        $user->givePermissionTo($name);

        return response()->json([
            "status" => "success",
            "data" => null
        ]);
    }

    public function revokeFromUser(Request $request)
    {
        if (!Auth::user()->can('users.manage-permissions')) {
            throw UnauthorizedException::forPermissions(['users.manage-permissions']);
        }

        $request->validate([
            'user_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        $name = $request->input('name');

        /** @var User $user */
        $user = User::findOrFail($request->input('user_id'));
        $user->revokePermissionTo($name);

        return response()->json([
            "status" => "success",
            "data" => null
        ]);
    }

}
