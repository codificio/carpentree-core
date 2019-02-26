<?php

namespace Carpentree\Core\Repositories;

use Carpentree\Core\Exceptions\ModelIsNotSearchable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BaseRepository
{
    const SORT_DELIMITER = ',';

    protected $model;
    protected $table;


    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    protected function search(Request $request)
    {
        if (method_exists($this->model, 'toSearchableArray') && $request->has('filter')) {
            $builder = $this->model::search($request->input('filter'));
        } else {
            throw ModelIsNotSearchable::create($this->model);
        }

        return $builder;
    }

    /**
     * Sort data based on a request compliant to JSON:API specification.
     *
     * @see https://jsonapi.org/format/#fetching-sorting
     * @param $builder
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    protected function sort(Request $request,  $builder = null)
    {
        if (is_null($builder))
        {
            $builder = $this->model::query();
        }

        $sort = $request->input('sort', null);

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
                    throw new \Exception(__("Column :column does not exists for model :model", ['column' => $col, 'model' => $this->model]));
                }

            }

        }

        return $builder;
    }
}
