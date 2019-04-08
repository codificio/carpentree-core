<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Builders\User\UserBuilderInterface;
use Carpentree\Core\DataAccess\User\UserDataAccess;
use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\SearchRequest;
use Carpentree\Core\Http\Requests\Admin\User\CreateUserRequest;
use Carpentree\Core\Http\Requests\Admin\User\UpdateUserRequest;
use Carpentree\Core\Http\Resources\UserResource;
use Carpentree\Core\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserController extends Controller
{

    /** @var UserBuilderInterface */
    protected $builder;

    /** @var UserDataAccess */
    protected $dataAccess;

    public function __construct(
        UserBuilderInterface $builder,
        UserDataAccess $dataAccess
    )
    {
        $this->builder = $builder;
        $this->dataAccess = $dataAccess;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list()
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        $users = $this->dataAccess->list();
        return UserResource::collection($users);
    }

    /**
     * @param SearchRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(SearchRequest $request)
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        $users = $this->dataAccess->fullTextSearch($request->input('filter.query'));
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

        return UserResource::make($this->dataAccess->findOrFail($id));
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function create(CreateUserRequest $request)
    {
        if (!Auth::user()->can('users.create')) {
            throw UnauthorizedException::forPermissions(['users.create']);
        }

        $attributes = $request->input('attributes');

        if (array_key_exists('password', $attributes)) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        $builder = $this->builder->init()->fill($attributes);

        if ($request->has('relationships.roles')) {
            $builder = $builder->withRoles($request->input('relationships.roles.data', array()));
        }

        $user = $builder->build();

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    /**
     * @param UpdateUserRequest $request
     * @return UserResource
     * @throws \Exception
     */
    public function update(UpdateUserRequest $request)
    {
        if (!Auth::user()->can('users.update')) {
            throw UnauthorizedException::forPermissions(['users.update']);
        }

        $user = $this->dataAccess->findOrFail($request->input('id'));

        $builder = $this->builder->init($user);

        if ($request->has('attributes')) {

            $attributes = $request->input('attributes');

            if (array_key_exists('password', $attributes)) {
                $attributes['password'] = Hash::make($attributes['password']);
            }

            $builder = $builder->fill($attributes);
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
        $user = $this->dataAccess->findOrFail($id);

        if ($this->dataAccess->delete($user)) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 202);
        }
    }

}
