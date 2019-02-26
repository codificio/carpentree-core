<?php

namespace Carpentree\Core\Repositories\Users;

use Carpentree\Core\Models\User;
use Carpentree\Core\Repositories\BaseRepository;
use Carpentree\Core\Repositories\Contracts\UserRepository;
use Illuminate\Http\Request;

class EloquentRepository extends BaseRepository implements UserRepository
{

    public function __construct()
    {
        $this->model = User::class;
        $temp = new User();
        $this->table = $temp->getTable();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function list(Request $request)
    {
        $query = $this->search($request);
        $query = $this->sort($request, $query);
        return $query->paginate(config('carpentree.pagination.per_page'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model::findOrFail($id);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model::create($attributes);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $entity = $this->model::findOrFail($id);
        return $entity->delete();
    }

}
