<?php

namespace Carpentree\Core\Http\Controllers;

use Carpentree\Core\Http\Resources\UserResource;
use Carpentree\Core\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserController extends Controller
{

    public function list()
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        User::all()->paginate(config('carpentree.items_per_page'));
    }

    public function get($id)
    {
        if (!Auth::user()->can('users.read')) {
            throw UnauthorizedException::forPermissions(['users.read']);
        }

        /** @var User $user */
        $user = User::findOrFail($id);

        return response()->json(UserResource::make($user));
    }

}
