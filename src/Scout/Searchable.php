<?php

namespace Carpentree\Core\Scout;

use Dimsav\Translatable\Translatable;
use Illuminate\Support\Facades\App;
use Laravel\Scout\Searchable as ParentTrait;

trait Searchable
{
    use ParentTrait {
        search as parentSearch;
    }

    /**
     * @param string $query
     * @param null $callback
     * @return \Laravel\Scout\Builder
     */
    public static function search($query = '', $callback = null)
    {
        $result = self::parentSearch($query, $callback);

        $localized = config('scout.localized', false);

        if ($localized && in_array(Translatable::class, class_uses_recursive(static::class))) {
            // Localized search
            $locale = App::getLocale();
            $temp = new static;
            $result = $result->within($temp->searchableAs() . "_$locale");
        }

        return $result;
    }
}
