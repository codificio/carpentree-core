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
        return $this->morphMany($this->getAddressModelClassName(), 'model', 'model_type','model_id');
    }

    /**
     * @return string
     */
    protected function getAddressModelClassName(): string
    {
        return Address::class;
    }

}
