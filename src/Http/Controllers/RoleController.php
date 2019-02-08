<?php

namespace Carpentree\Core\Http\Controllers;

use Carpentree\Core\Http\Resources\RoleResource;
use Carpentree\Core\Models\User;
use Carpentree\Core\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

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

    /**
     * @param $id
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
        $user = $this->userRepository->find($id);
        $user->syncRoles($request->input('roles'));

        return response()->json();
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
        $user = $this->userRepository->find($id);
        $user->removeRole($name);

        return response()->json();
    }

}
