<?php

namespace Carpentree\Core\Scout;

use Illuminate\Support\Facades\App;
use Laravel\Scout\Searchable as ParentTrait;

trait Searchable
{
    use ParentTrait;

    /**
     * Return true if you want to store this model in localized index.
     *
     * @return bool
     */
    public static function localizedSearchable()
    {
        return false;
    }

    /**
     * @param string $query
     * @param null $callback
     * @return \Laravel\Scout\Builder
     */
    public static function search($query = '', $callback = null)
    {
        if (static::localizedSearchable()) {
            $model = new static;
            $index = $model->searchableAs() . '_' . App::getLocale();
            $result = ParentTrait::search($query, $callback)->within($index);
        } else {
            $result = ParentTrait::search($query, $callback);
        }

        return $result;
    }
}
