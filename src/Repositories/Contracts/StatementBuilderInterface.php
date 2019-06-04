<?php

namespace Carpentree\Core\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface StatementBuilderInterface
{
    /**
     * @param Builder $query
     * @param $field
     * @param $operator
     * @param $value
     * @param string $joinOperator
     * @param bool $isFirst
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeWhereStatement(Builder &$query, $field, $operator, $value, $joinOperator = 'and', $isFirst = false);

    /**
     * @param Builder $query
     * @param $field
     * @param string $direction
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeOrderByStatement(Builder &$query, $field, $direction = 'asc');
}
