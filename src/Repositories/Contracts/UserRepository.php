<?php

namespace Carpentree\Core\Repositories\Contracts;

use Illuminate\Http\Request;

interface UserRepository
{
    public function list(Request $request);

    public function find($id);

    public function create(array $attributes);

    public function delete($id);
}
