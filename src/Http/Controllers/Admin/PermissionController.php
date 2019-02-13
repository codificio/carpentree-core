<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Resources\PermissionResource;
use Carpentree\Core\Models\User;
use Illuminate\Http\Request;
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncWithUser($id, Request $request)
    {
        if (!Auth::user()->can('users.manage-permissions')) {
            throw UnauthorizedException::forPermissions(['users.manage-permissions']);
        }

        $request->validate([
            'permissions' => 'required|array',
        ]);

        /** @var User $user */
        $user = User::findOrFail($id);
        $user->syncPermissions($request->input('permissions'));

        return response()->json();
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeFromUser($id, Request $request)
    {
        if (!Auth::user()->can('users.manage-permissions')) {
            throw UnauthorizedException::forPermissions(['users.manage-permissions']);
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $name = $request->input('name');

        /** @var User $user */
        $user = User::findOrFail($id);
        $user->revokePermissionTo($name);

        return response()->json();
    }

}
