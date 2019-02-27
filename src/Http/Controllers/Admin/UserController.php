<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\CreateUserRequest;
use Carpentree\Core\Http\Requests\Admin\UpdateUserRequest;
use Carpentree\Core\Http\Resources\UserResource;
use Carpentree\Core\Models\User;
use Carpentree\Core\Services\Listing\User\UserListingInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

            // Create user
            $attributes = $request->input('attributes');
            /** @var User $user */
            $user = User::create($attributes);

            // Sync roles
            $roles = $request->input('relationships.roles', array());
            $user->syncRoles($roles);

            return $user;
        });

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    public function update(UpdateUserRequest $request)
    {
        if (!Auth::user()->can('users.update')) {
            throw UnauthorizedException::forPermissions(['users.update']);
        }

        // TODO: refactoring of user update

        /** @var User $user */
        $user = DB::transaction(function() use ($request) {

            // Update user
            $id = $request->input('id');
            /** @var User $user */
            $user = User::findOrFail($id);
            if ($request->has('attributes')) {
                $attributes = $request->input('attributes');
                $user = $user->fill($attributes);
            }

            // Sync roles
            if ($request->has('relationships.roles')) {
                $roles = $request->input('relationships.roles');
                $user = $user->syncRoles($roles);
            }

            // Meta fields
            if ($request->has('relationships.meta')) {
                $meta = $request->input('relationships.meta');
                $user = $user->syncMeta($meta);
            }

            $user->save();

            return $user;
        });

        return UserResource::make($user);
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
