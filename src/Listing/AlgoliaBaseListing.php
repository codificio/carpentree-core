<?php

namespace Carpentree\Core\Listing;

use Carpentree\Core\Exceptions\ModelIsNotSearchable;
use Illuminate\Support\Facades\App;

class AlgoliaBaseListing extends BaseListing
{
    /**
     * Perform a full text search.
     *
     * @param string $query
     * @return mixed
     */
    protected function search(string $query)
    {
        if (!method_exists($this->model, 'toSearchableArray')) {
            throw ModelIsNotSearchable::create($this->model);
        }

        if ($this->model::localizedSearchable()) {

            $index = $this->model::first()->searchableAs() . '_' . App::getLocale();
            $result = $this->model::search($query)->within($index);

        } else {

            $result = $this->model::search($query);

        }


        return $result;
    }

}
