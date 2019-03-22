<?php

namespace Carpentree\Core\DataAccess;

interface BaseDataAccess
{
    public function find($id);

    public function findOrFail($id);

    public function all();

    public function delete($model);
}
