<?php

namespace Carpentree\Core\Repositories\Contracts;

use Illuminate\Http\Request;

interface UserRepository
{
    public function list(Request $request);

    public function get($id);
}
