<?php

namespace Carpentree\Core\Repositories\Criteria;

use Carpentree\Core\Exceptions\RepositoryException;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Carpentree\Core\Repositories\Contracts\CriteriaInterface;
use Carpentree\Core\Repositories\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class RequestCriteria
 *
 * @package Carpentre\Core\Repositories
 */
class RequestCriteria implements CriteriaInterface
{

    const DEFAULT_OPERATOR = '=';
    const DEFAULT_DIRECTION = 'asc';

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param         Builder|Model $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $fieldsSearchable = $this->parseFieldsSearchable($repository->getFieldsSearchable());
        $search = $this->request->get(config('carpentree.repository.criteria.params.search', 'search'), null);
        // $filter = $this->request->get(config('carpentree.repository.criteria.params.filter', 'filter'), null);
        $orderBy = $this->request->get(config('carpentree.repository.criteria.params.orderBy', 'orderBy'), null);
        $with = $this->request->get(config('carpentree.repository.criteria.params.with', 'with'), null);
        // $searchJoin = $this->request->get(config('carpentree.repository.criteria.params.searchJoin', 'searchJoin'), null);

        if ($search) {
            $searchData = $this->parseSearchData($search);

            $model = $model->where(function ($query) use ($model, $searchData, $fieldsSearchable) {
                /** @var Builder $query */

                foreach ($searchData as $args) {

                    $simpleSearch = false;

                    switch (count($args)) {
                        case 2:
                            // field:value
                            list($field, $value) = $args;
                            $operator = array_key_exists($field, $fieldsSearchable) ? $fieldsSearchable[$field] : self::DEFAULT_OPERATOR;
                            break;

                        case 3:
                            // field:operator:value
                            list($field, $operator, $value) = $args;
                            break;

                        default:
                            // only value
                            list($value) = $args;
                            $simpleSearch = true;
                            break;
                    }

                    if ($simpleSearch) {
                        // Simple search: search for $value in all fields listed in $fieldsSearchable.

                        $query->where(function ($query) use ($fieldsSearchable, $value, $model) {

                            $i = 0;
                            foreach ($fieldsSearchable as $field => $operator) {
                                $isFirst = $i == 0 ? true : false;
                                $operator = isset($operator) ? $operator : self::DEFAULT_OPERATOR;

                                $fieldIsTranslatable = $this->fieldIsTranslatable($model->getModel(), $field);

                                if ($this->fieldExists($model->getModel(), $field, $fieldIsTranslatable)) {
                                    $this->makeWhereStatement($query, $field, $operator, $value, $fieldIsTranslatable, 'or', $isFirst);
                                    $i++;
                                }
                            }

                        });

                    } else {

                        $fieldIsTranslatable = $this->fieldIsTranslatable($model->getModel(), $field);

                        if ($this->fieldExists($model->getModel(), $field, $fieldIsTranslatable)) {
                            $this->makeWhereStatement($query, $field, $operator, $value, $fieldIsTranslatable);
                        }

                    }

                }

            });
        }

        if (isset($orderBy) && !empty($orderBy)) {

            $orderByData = $this->parseOrderByData($orderBy);

            foreach ($orderByData as $args) {

                switch (count($args)) {
                    case 2:
                        // field:direction
                        list($field, $direction) = $args;
                        break;

                    default:
                        // only field
                        list($field) = $args;
                        $direction = self::DEFAULT_DIRECTION;
                        break;
                }

                $this->makeOrderByStatement($model, $field, $direction);
            }
        }

        if ($with) {
            $with = explode(';', $with);
            $model = $model->with($with);
        }

        return $model;
    }

    /**
     * Check if model has a specific column.
     *
     * @param Model $model
     * @param $field
     * @param bool $translatable
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function fieldExists($model, $field, $translatable = false)
    {
        if (stripos($field, '.')) {
            $explode = explode('.', $field);
            $field = array_pop($explode);

            foreach ($explode as $relation) {
                $model = $model->{$relation}()->getModel();
            }
        }

        if ($translatable) {
            /** @var Translatable $model */
            $translatedTable = app()->make($model->getTranslationModelName())->getTable();
            return Schema::hasColumn($translatedTable, $field);
        } else {
            return Schema::hasColumn($model->getTable(), $field);
        }
    }

    /**
     * Check if given field is translatable
     *
     * @param Model $model
     * @param $field
     * @return bool
     */
    protected function fieldIsTranslatable($model, $field)
    {
        if (stripos($field, '.')) {
            $explode = explode('.', $field);
            $field = array_pop($explode);

            foreach ($explode as $relation) {
                $model = $model->{$relation}()->getModel();
            }
        }

        if (in_array(Translatable::class, class_uses_recursive($model))) {
            return in_array($field, $model->translatedAttributes);
        }

        return false;
    }

    /**
     * @param $fields
     * @return array
     */
    protected function parseFieldsSearchable($fields)
    {
        $acceptedConditions = config('repository.criteria.acceptedConditions', [
            '=',
            'like'
        ]);

        $newFields = [];

        foreach ($fields as $key => $value) {

            if (is_int($key)) {
                // Only array value. I'll use default operator
                $newFields[$value] = self::DEFAULT_OPERATOR;
            } else {
                if (!in_array($value, $acceptedConditions)) {
                    throw new BadRequestHttpException("Operator $value is no an accepted operator");
                }

                $newFields[$key] = $value;
            }

        }

        return $newFields;
    }

    /**
     * @param $orderBy
     * @return array
     */
    protected function parseOrderByData($orderBy)
    {
        $orderByData = [];

        $orders = explode(';', $orderBy);

        foreach ($orders as $row) {
            $orderByData[] = explode(':', $row);
        }

        return $orderByData;
    }

    /**
     * @param $search
     *
     * @return array
     */
    protected function parseSearchData($search)
    {
        $searchData = [];

        $fields = explode(';', $search);

        foreach ($fields as $row) {
            $searchData[] = explode(':', $row);
        }

        return $searchData;
    }

    /**
     * @param Builder $query
     * @param $field
     * @param $operator
     * @param $value
     * @param bool $translatable
     * @param string $joinOperator
     * @param bool $isFirst
     */
    private function makeWhereStatement(Builder &$query, $field, $operator, $value, $translatable = false, $joinOperator = 'and', $isFirst = false)
    {
        $modelTableName = $query->getModel()->getTable();

        $value = ($operator == 'like' || $operator == 'ilike') ? "%$value%" : $value;

        $relation = null;
        if (stripos($field, '.')) {
            $explode = explode('.', $field);
            $field = array_pop($explode);
            $relation = implode('.', $explode);
            $relation .= $translatable ? '.translations' : '';
        }

        if ($isFirst || $joinOperator == 'and') {

            if (!is_null($relation)) {
                $query->whereHas($relation, function ($query) use ($field, $operator, $value) {
                    /** @var Builder $query */
                    $query->where($field, $operator, $value);
                });
            } else {
                $query->where($modelTableName . '.' . $field, $operator, $value);
            }

        } elseif ($joinOperator == 'or') {

            if (!is_null($relation)) {
                $query->orWhereHas($relation, function ($query) use ($field, $operator, $value) {
                    /** @var Builder $query */
                    $query->where($field, $operator, $value);
                });
            } else {
                $query->orWhere($modelTableName . '.' . $field, $operator, $value);
            }

        }

    }

    private function makeOrderByStatement(Builder &$query, $field, $direction)
    {
        $relation = null;
        if (stripos($field, '.')) {
            $explode = explode('.', $field);
            $field = array_pop($explode);
            $relation = implode('.', $explode);
        }

        if (!is_null($relation)) {
            $query->with([$relation => function ($q) use ($field, $direction) {
                /** @var Builder $q */
                $q->orderBy($field, $direction);
            }]);
        } else {
            $query->orderBy($field, $direction);
        }
    }
}
