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
        DB::transaction(function() use ($meta) {
            $metaToSave = array();
            $idsToMaintain = array();

            foreach ($meta as $field) {
                if ($meta = $this->meta()->where('key', $field['key'])->first()) {

                    // Update
                    foreach ($field as $key => $value) {
                        $meta->$key = $value;
                    }

                    $metaToSave[] = $meta;
                    $idsToMaintain[] = $meta->id;

                } else {

                    // Create
                    $meta = new MetaField($field);
                    $metaToSave[] = $meta;

                }
            }

            // Removing old fields
            MetaField::whereNotIn('id', $idsToMaintain)->delete();

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
