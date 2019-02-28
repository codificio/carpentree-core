<?php

namespace Carpentree\Core\Scout;

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
}
