<?php

namespace Carpentree\Core\Repositories\Eloquent;

use Carpentree\Core\Exceptions\RepositoryException;
use Carpentree\Core\Repositories\Contracts\CriteriaInterface;
use Carpentree\Core\Repositories\Contracts\RepositoryCriteriaInterface;
use Carpentree\Core\Repositories\Contracts\RepositoryInterface;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository implements RepositoryInterface, RepositoryCriteriaInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Collection of Criteria
     *
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @param Application $app
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->criteria = new Collection();
        $this->makeModel();
        $this->boot();
    }

    /**
     * Boot method
     */
    public function boot()
    {
        //
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract public function model();

    /**
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * @return Model|mixed
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Get Searchable Fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Get Collection of Criteria
     *
     * @return Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param array $columns
     * @return mixed
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();

        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetModel();

        return $results;
    }

    /**
     * Apply criteria in current Query
     *
     * @return $this
     */
    protected function applyCriteria()
    {

        if ($this->skipCriteria === true) {
            return $this;
        }

        $criteria = $this->getCriteria();

        if ($criteria) {
            foreach ($criteria as $c) {
                if ($c instanceof CriteriaInterface) {
                    $this->model = $c->apply($this->model, $this);
                }
            }
        }

        return $this;
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     * @return mixed
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $results;
    }

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        // TODO: Implement find() method.
    }

    /**
     * Load relations
     *
     * @param $relations
     * @return $this
     */
    public function with($relations)
    {
        // TODO: Implement with() method.
    }

    /**
     * Order collection by a given column
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        // TODO: Implement orderBy() method.
    }


    /**
     * Push Criteria for filter the query
     *
     * @param $criteria
     * @return $this
     * @throws RepositoryException
     */
    public function pushCriteria($criteria)
    {
        if (is_string($criteria)) {
            $criteria = new $criteria;
        }
        if (!$criteria instanceof CriteriaInterface) {
            throw new RepositoryException("Class " . get_class($criteria) . " must be an instance of Carpentree\\Core\\Repositories\\Contracts\\CriteriaInterface");
        }
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * Pop Criteria
     *
     * @param $criteria
     *
     * @return $this
     */
    public function popCriteria($criteria)
    {
        $this->criteria = $this->criteria->reject(function ($item) use ($criteria) {
            if (is_object($item) && is_string($criteria)) {
                return get_class($item) === $criteria;
            }

            if (is_string($item) && is_object($criteria)) {
                return $item === get_class($criteria);
            }

            return get_class($item) === get_class($criteria);
        });

        return $this;
    }

    /**
     * Find data by Criteria
     *
     * @param CriteriaInterface $criteria
     * @return mixed
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getByCriteria(CriteriaInterface $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        $results = $this->model->get();
        $this->resetModel();

        return $results;
    }

    /**
     * Skip Criteria
     *
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * Reset all Criterias
     *
     * @return $this
     */
    public function resetCriteria()
    {
        $this->criteria = new Collection();

        return $this;
    }
}
