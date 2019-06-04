<?php

namespace Carpentree\Core\Repositories;

use Carpentree\Core\Repositories\Criteria\RequestCriteriaEloquent;
use Carpentree\Core\Repositories\Eloquent\BaseRepository;
use Carpentree\Core\Models\User;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace Carpentree\Core\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{

    protected $fieldSearchable = [
        'first_name' => 'like',
        'last_name' => 'like',
        'email' => 'like'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }


    /**
     * Boot up the repository, pushing criteria
     * @throws \Carpentree\Core\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteriaEloquent::class));
    }
}
