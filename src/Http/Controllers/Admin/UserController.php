<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\CreateUserRequest;
use Carpentree\Core\Http\Resources\UserResource;
use Carpentree\Core\Models\User;
use Carpentree\Core\Services\Listing\User\UserListingInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    /** @var UserListingInterface */
    protected $listingService;

    public function __construct(UserListingInterface $listingService)
    {
        $this->listingService = $listingService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(Request $request)
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        $users = $this->listingService->list($request);
        return UserResource::collection($users);
    }

    /**
     * @param $id
     * @return UserResource
     */
    public function get($id)
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        return UserResource::make(User::findOrFail($id));
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateUserRequest $request)
    {
        if (!Auth::user()->can('users.create')) {
            throw UnauthorizedException::forPermissions(['users.create']);
        }

        // TODO: refactoring of user creation
        $user = DB::transaction(function() use ($request) {
            $attributes = $request->input('attributes');
            $roles = $request->input('relationships.roles', array());

            /** @var User $user */
            $user = User::create($attributes);

            foreach ($roles as $role)
            {
                $role = Role::findOrFail($role->id);
                $user->assignRole($role);
            }
        });

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    public function update($id)
    {
        if (!Auth::user()->can('users.update')) {
            throw UnauthorizedException::forPermissions(['users.update']);
        }

        /** @var User $user */
        $user = User::findOrFail($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($id)
    {
        if (!Auth::user()->can('users.delete')) {
            throw UnauthorizedException::forPermissions(['users.delete']);
        }

        /** @var User $user */
        $user = User::findOrFail($id);

        if ($user->delete($id)) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 202);
        }
    }

}
