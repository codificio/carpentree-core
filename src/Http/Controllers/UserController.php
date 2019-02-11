<?php

namespace Carpentree\Core\Http\Controllers;

use Carpentree\Core\Http\Requests\CreateUserRequest;
use Carpentree\Core\Http\Resources\UserResource;
use Carpentree\Core\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list()
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        return UserResource::collection($this->repository->paginate());
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

        return UserResource::make($this->repository->find($id));
    }

    /**
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function create(CreateUserRequest $request)
    {
        if (!Auth::user()->can('users.create')) {
            throw UnauthorizedException::forPermissions(['users.create']);
        }

        $attributes = $request->input('attributes');
        $user = $this->repository->create($attributes);

        return UserResource::make($user)->response()->setStatusCode(201);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        if (!Auth::user()->can('users.create')) {
            throw UnauthorizedException::forPermissions(['users.delete']);
        }

        if ($this->repository->delete($id)) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 202);
        }
    }

}
