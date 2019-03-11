<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Builders\User\UserBuilderInterface;
use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\ListRequest;
use Carpentree\Core\Http\Requests\Admin\User\CreateUserRequest;
use Carpentree\Core\Http\Requests\Admin\User\UpdateUserRequest;
use Carpentree\Core\Http\Resources\UserResource;
use Carpentree\Core\Models\User;
use Carpentree\Core\Services\Listing\User\UserListingInterface;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserController extends Controller
{

    /** @var UserListingInterface */
    protected $listingService;

    /** @var UserBuilderInterface */
    protected $builder;

    public function __construct(UserListingInterface $listingService, UserBuilderInterface $builder)
    {
        $this->listingService = $listingService;
        $this->builder = $builder;
    }

    /**
     * @param ListRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(ListRequest $request)
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

        $builder = $this->builder->init()->create($request->input('attributes'));

        if ($request->has('relationships.roles')) {
            $builder = $builder->withRoles($request->input('relationships.roles.data', array()));
        }

        $user = $builder->build();

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    /**
     * @param UpdateUserRequest $request
     * @return UserResource
     */
    public function update(UpdateUserRequest $request)
    {
        if (!Auth::user()->can('users.update')) {
            throw UnauthorizedException::forPermissions(['users.update']);
        }

        $user = User::findOrFail($request->input('id'));

        $builder = $this->builder->init($user);

        if ($request->has('attributes')) {
            $builder = $builder->create($request->input('attributes'));
        }

        if ($request->has('relationships.roles')) {
            $builder = $builder->withRoles($request->input('relationships.roles.data', array()));
        }

        if ($request->has('relationships.meta')) {
            $_data = $request->input('relationships.meta.data', array());
            $_attributes = collect($_data)->pluck('attributes')->toArray();
            $builder = $builder->withMeta($_attributes);
        }

        $user = $builder->build();

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
