<?php

namespace Carpentree\Core\Repositories\Criteria\Request;

use Carpentree\Core\Repositories\Contracts\StatementBuilderInterface;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DimsavStatementBuilder implements StatementBuilderInterface
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
    public function makeWhereStatement(Builder &$query, $field, $operator, $value, $joinOperator = 'and', $isFirst = false)
    {
        $translatable = $this->fieldIsTranslatable($query->getModel(), $field);

        if (!$this->fieldExists($query->getModel(), $field, $translatable)) {
            return false;
        }

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

            return true;

        } elseif ($joinOperator == 'or') {

            if (!is_null($relation)) {
                $query->orWhereHas($relation, function ($query) use ($field, $operator, $value) {
                    /** @var Builder $query */
                    $query->where($field, $operator, $value);
                });
            } else {
                $query->orWhere($modelTableName . '.' . $field, $operator, $value);
            }

            return true;
        }

        return false;
    }

    /**
     * @param Builder $query
     * @param $field
     * @param string $direction
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeOrderByStatement(Builder &$query, $field, $direction = 'asc')
    {
        $translatable = $this->fieldIsTranslatable($query->getModel(), $field);

        if (!$this->fieldExists($query->getModel(), $field, $translatable)) {
            return false;
        }

        $relation = null;
        if (stripos($field, '.')) {
            $explode = explode('.', $field);
            $field = array_pop($explode);
            $relation = implode('.', $explode);
            $relation .= $translatable ? '.translations' : '';
        }

        if (!is_null($relation)) {
            $query->with([$relation => function ($q) use ($field, $direction) {
                /** @var Builder $q */
                $q->orderBy($field, $direction);
            }]);
        } else {
            $query->orderBy($field, $direction);
        }

        return true;
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
}
