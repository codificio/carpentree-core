<?php

namespace Carpentree\Core\Repositories\MetaFields;

use Carpentree\Core\Exceptions\ModelHasNotMetaFields;
use Carpentree\Core\Models\MetaField;
use Carpentree\Core\Repositories\BaseRepository;
use Carpentree\Core\Repositories\Contracts\MetaFieldsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class EloquentRepository extends BaseRepository implements MetaFieldsRepository
{

    public function __construct()
    {
        $this->model = MetaField::class;
        $temp = new MetaField();
        $this->table = $temp->getTable();
    }

    /**
     * @param Model $entity
     * @return mixed
     * @throws \Exception
     */
    public function getByEntity(Model $entity)
    {
        $this->entityHasMeta($entity);

        return $entity->meta;
    }

    public function find($id)
    {
        return $this->model::findOrFail($id);
    }

    public function create(Model $entity, array $attributes, $locale = null)
    {
        $this->entityHasMeta($entity);

        if (!is_null($locale)) {
            App::setLocale($locale);
        }

        $entity->meta()->create($attributes);

        return $entity;
    }

    public function delete($id)
    {
        $entity = $this->model::findOrFail($id);
        return $entity->delete();
    }

    /**
     * Check if entity has meta fields.
     *
     * @param Model $entity
     * @return bool
     */
    private function entityHasMeta(Model $entity)
    {
        if (!method_exists($entity, 'meta')) {
            throw ModelHasNotMetaFields::create(get_class($entity));
        }

        return true;
    }
}
