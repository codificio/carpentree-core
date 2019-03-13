<?php

namespace Carpentree\Core\Traits;

use Carpentree\Core\Models\Address;

trait HasAddresses
{
    /**
     * @return mixed
     */
    public function addresses()
    {
        return $this->morphMany($this->getMetaModelClassName(), 'model', 'model_type','model_id');
    }

    /**
     * @return string
     */
    protected function getMetaModelClassName(): string
    {
        return Address::class;
    }

}
