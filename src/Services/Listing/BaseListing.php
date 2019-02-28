<?php

namespace Carpentree\Core\Services\Listing;

use Carpentree\Core\Exceptions\ModelIsNotSearchable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

/**
 * Class BaseListingService
 *
 * Filter entity based on Http Request.
 * Compliant to JSON:API specification.
 *
 * @see https://jsonapi.org/format/#fetching-sorting
 * @package Carpentree\Core\Services\Listing
 */
class BaseListing implements ListingInterface
{
    const SORT_DELIMITER = ',';

    protected $model;
    protected $table;

    public function list(Request $request)
    {
        $builder = $this->model::query();

        // Full text search
        if ($request->has('filter.query')) {
            $query = $request->input('filter.query');
            $builder = $this->search($query);
        }

        // Sorting
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $builder = $this->sort($sort, $builder);
        }

        return $builder->paginate(config('carpentree.core.pagination.per_page'));
    }

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

    /**
     * Sort data based on a request compliant to JSON:API specification.
     *
     * @param string $sort
     * @param $builder
     * @return mixed
     * @throws Exception
     */
    protected function sort(string $sort, $builder = null)
    {
        if (is_null($builder)) {
            $builder = $this->model::query();
        }

        if (is_null($sort)) {

            if (Schema::hasColumn($this->table, 'created_at')) {
                $builder->orderBy('created_at', 'DESC');
            }

        } else {

            $sortArray = explode(self::SORT_DELIMITER, $sort);

            foreach ($sortArray as $s) {

                $dir = substr($s, 0, 1) == '-' ? 'DESC' : 'ASC';

                $col = $dir == 'DESC' ? substr($s, 1) : $s;

                if (Schema::hasColumn($this->table, $col)) {
                    $builder->orderBy($col, $dir);
                } else {
                    throw new Exception(__("Column :column does not exists for model :model", ['column' => $col, 'model' => $this->model]));
                }
            }
        }

        return $builder;
    }
}
