<?php

namespace Carpentree\Core\Traits;

use Carpentree\Core\Models\MetaField;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

trait HasMeta
{
    /**
     * @return mixed
     */
    public function meta()
    {
        return $this->morphMany($this->getMetaModelClassName(), 'model', 'model_type','model_id');
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getMetaByKeyOrFail($key)
    {
        $meta = $this->getMetaByKey($key);

        if (!$meta) {
            throw new NotFoundHttpException(__("Meta field with key :key was not found for model :model with id :id", [
                'key' => $key,
                'model' => get_class($this),
                'id' => $this->id
            ]));
        }

        return $meta;
    }

    /**
     * @param $key
     * @return bool
     */
    public function getMetaByKey($key)
    {
        $meta = $this->meta()->where('key', $key)->first();

        return $meta ?? false;
    }


    /**
     * @param array $meta
     * @return mixed
     * @throws Exception
     */
    public function createOrUpdateMeta(array $meta)
    {
        if (!array_key_exists('key', $meta) || !array_key_exists('value', $meta)) {
            throw new Exception(__("Missing key or value of the meta fields for model of id :id", ['id' => $this->id]));
        }

        $_meta = $this->getMetaByKey($meta['key']);

        if ($_meta) {
            $_meta->value = $meta['value'];
        } else {
            $_meta = new MetaField($meta);
        }

        return $this->meta()->save($_meta);
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function syncMeta(array $meta)
    {
        // Works only with array like:
        // [
        //   'key' => ...,
        //   'value' => ...
        // ]
        DB::transaction(function() use ($meta) {
            $metaToSave = array();
            $idsToMaintain = array();

            foreach ($meta as $field) {

                $_meta = $this->meta()
                    ->where('key', $field['key'])
                    ->first();

                if ($_meta) {

                    // Update
                    foreach ($field as $key => $value) {
                        $_meta->$key = $value;
                    }

                    $metaToSave[] = $_meta;
                    $idsToMaintain[] = $_meta->id;

                } else {

                    // Create
                    $_meta = new MetaField($field);
                    $metaToSave[] = $_meta;

                }
            }

            // Removing old fields
            $this->meta()->whereNotIn('id', $idsToMaintain)->delete();

            // Save new fields
            $this->meta()->saveMany($metaToSave);
        });

        return $this;
    }

    /**
     * @return string
     */
    protected function getMetaModelClassName(): string
    {
        return MetaField::class;
    }
}
