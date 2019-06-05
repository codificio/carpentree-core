<?php

namespace Carpentree\Core\Repositories\Contracts;

use Carpentree\Core\Exceptions\RepositoryException;

interface RepositoryInterface
{
    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*']);

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * Load relations
     *
     * @param $relations
     * @return $this
     */
    public function with($relations);

    /**
     * Order collection by a given column
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc');

    /**
     * Get Searchable Fields
     *
     * @return array
     */
    public function getFieldsSearchable();

    /**
     * Retrieve first data of repository
     *
     * @param array $columns
     * @return mixed
     */
    public function first($columns = ['*']);

    /**
     * Retrieve data of repository
     *
     * @param array $columns
     * @return mixed
     * @throws RepositoryException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function get($columns = ['*']);
}
