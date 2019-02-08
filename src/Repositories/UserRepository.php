<?php

namespace Carpentree\Core\Repositories;

use Carpentree\Core\Models\User;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return User::class;
    }
}
