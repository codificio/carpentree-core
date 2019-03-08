<?php

namespace Carpentree\Core\Http\Controllers\Admin;

use Carpentree\Core\Http\Builders\Address\AddressBuilderInterface;
use Carpentree\Core\Http\Controllers\Controller;
use Carpentree\Core\Http\Requests\Admin\Address\CreateAddressRequest;
use Carpentree\Core\Http\Requests\Admin\Address\UpdateAddressRequest;
use Carpentree\Core\Http\Resources\AddressResource;
use Carpentree\Core\Models\Address;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class AddressController extends Controller
{
    /** @var AddressBuilderInterface */
    protected $builder;

    /**
     * AddressController constructor.
     * @param AddressBuilderInterface $builder
     */
    public function __construct(AddressBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param $id
     * @return AddressResource
     */
    public function get($id)
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        return AddressResource::make(Address::findOrFail($id));
    }

    /**
     * @param CreateAddressRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateAddressRequest $request)
    {
        if (!Auth::user()->can('users.create')) {
            throw UnauthorizedException::forPermissions(['users.create']);
        }

        $address = $this->builder->init()
            ->create($request->input('attributes'))
            ->withUser($request->input('relationships.user.data'))
            ->withAddressType($request->input('relationships.type.data'))
            ->build();

        return AddressResource::make($address)->response()->setStatusCode(201);
    }

    /**
     * @param UpdateAddressRequest $request
     * @return AddressResource
     */
    public function update(UpdateAddressRequest $request)
    {
        if (!Auth::user()->can('users.update')) {
            throw UnauthorizedException::forPermissions(['users.update']);
        }

        $address = Address::findOrFail($request->input('id'));

        $builder = $this->builder->init($address);

        if ($request->has('attributes')) {
            $builder = $builder->create($request->input('attributes'));
        }

        if ($request->has('relationships.user')) {
            $builder = $builder->withUser($request->input('relationships.user.data'));
        }

        if ($request->has('relationships.type')) {
            $builder = $builder->withAddressType($request->input('relationships.type.data'));
        }

        $address = $builder->build();

        return AddressResource::make($address);
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

        /** @var Address $address */
        $address = Address::findOrFail($id);

        if ($address->delete($id)) {
            return response()->json(null, 204);
        } else {
            return response()->json(null, 202);
        }
    }
}
