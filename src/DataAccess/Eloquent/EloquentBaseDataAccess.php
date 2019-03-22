<?php

namespace Carpentree\Core\DataAccess\Eloquent;

use Carpentree\Core\DataAccess\BaseDataAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Exception;

class EloquentBaseDataAccess implements BaseDataAccess
{

    /** @var string $class */
    protected $class;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id)
    {
        return $this->class::find($id);
    }

    /**
     * @param $id
     * @return Model
     */
    public function findOrFail($id)
    {
        return $this->class::findOrFail($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->class::all();
    }

    /**
     * @param Builder $builder
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function get(Builder $builder)
    {
        return $builder->get();
    }

    /**
     * @param Builder $builder
     * @return Builder|Model|object|null
     */
    protected function first(Builder $builder)
    {
        return $builder->first();
    }

    /**
     * @param Builder $builder
     * @param null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function paginate(Builder $builder, $perPage = null)
    {
        $perPage = $perPage ?: config('carpentree.core.pagination.per_page');
        return $builder->paginate($perPage);
    }

    /**
     * @param Model $model
     * @return bool|mixed|null
     * @throws Exception
     */
    public function delete($model)
    {
        return $model->delete();
    }
}
