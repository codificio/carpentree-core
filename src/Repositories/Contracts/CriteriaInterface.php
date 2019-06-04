<?php

namespace Carpentree\Core\Repositories\Contracts;

/**
 * Interface CriteriaInterface
 *
 * @package Carpentree\Core\Repositories\Contracts
 */
interface CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository);
}
