<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Events\UserDeleted;
use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\CreateUserRequest;
use Carpentree\Core\Http\Resources\UserResource;
use Carpentree\Core\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserController extends Controller
{


    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list()
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        return UserResource::collection(User::paginate(config('carpentree.pagination.per_page')));
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

        $user = User::findOrFail($id);

        return UserResource::make($user);
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

        $attributes = $request->input('attributes');
        $user = User::create($attributes);

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($id)
    {
        if (!Auth::user()->can('users.create')) {
            throw UnauthorizedException::forPermissions(['users.delete']);
        }

        /** @var User $user */
        $user = User::findOrFail($id);

        if ($user->delete()) {
            event(new UserDeleted($id));
            return response()->json(null, 204);
        } else {
            return response()->json(null, 202);
        }
    }

}
