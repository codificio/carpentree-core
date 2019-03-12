<?php

namespace Carpentree\Core\Traits;

use Carpentree\Core\Models\MetaField;
use Illuminate\Support\Facades\DB;

trait HasMeta
{
    public function meta()
    {
        return $this->morphMany($this->getMetaModelClassName(), 'model', 'model_type','model_id');
    }

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

    protected function getMetaModelClassName(): string
    {
        return MetaField::class;
    }
}
