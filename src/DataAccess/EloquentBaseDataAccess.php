<?php

namespace Carpentree\Core\DataAccess;

use Carpentree\Core\Exceptions\ModelIsNotSearchable;
use Carpentree\Core\Scout\Searchable;
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
     * @param string $query
     * @return mixed
     */
    public function fullTextSearch($query = '')
    {
        if (!in_array(Searchable::class, class_uses($this->class))) {
            throw ModelIsNotSearchable::create($this->class);
        }

        return $this->class::search($query)->paginate(config('carpentree.core.pagination.per_page'));
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
