<?php

namespace Carpentree\Core\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface MetaFieldsRepository
{
    public function getByEntity(Model $entity);

    public function find($id);

    public function create(Model $entity, array $attributes);

    public function delete($id);
}
