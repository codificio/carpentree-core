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
     * @param $locale
     * @return Searchable
     */
    public function pushLocaleMetadata($locale)
    {
        return $this->withScoutMetadata('locale', $locale);
    }

    /**
     * @param string $query
     * @param null $callback
     * @return \Laravel\Scout\Builder
     */
    public static function search($query = '', $callback = null)
    {
        $result = self::parentSearch($query, $callback);

        if (in_array(Translatable::class, class_uses_recursive(static::class))) {
            // Localized search
            $result = $result->where('locale', App::getLocale());
        }

        return $result;
    }
}
