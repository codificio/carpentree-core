<?php

namespace Carpentree\Core\Traits;

use Carpentree\Core\Models\MetaField;

trait HasMeta
{
    public function meta()
    {
        return $this->morphMany($this->getMetaModelClassName(), 'model', 'model_type','model_id');
    }

    protected function getMetaModelClassName(): string
    {
        return MetaField::class;
    }
}
