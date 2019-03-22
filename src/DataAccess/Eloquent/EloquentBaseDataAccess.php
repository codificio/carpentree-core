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
     * @param Model $model
     * @return bool|mixed|null
     * @throws Exception
     */
    public function delete($model)
    {
        return $model->delete();
    }
}
