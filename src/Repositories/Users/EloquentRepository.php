<?php

namespace Carpentree\Core\Repositories\Users;

use Carpentree\Core\Models\User;
use Carpentree\Core\Repositories\BaseRepository;
use Carpentree\Core\Repositories\Contracts\UserRepository;

class EloquentRepository extends BaseRepository implements UserRepository
{

    public function __construct()
    {
        $this->model = User::class;
        $temp = new User();
        $this->table = $temp->getTable();
    }

}
