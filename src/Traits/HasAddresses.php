<?php

namespace Carpentree\Core\Traits;

use Carpentree\Core\Models\Address;
use Illuminate\Support\Facades\DB;

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

    /**
     * @param array $addresses
     * @return $this
     */
    public function syncAddresses(array $addresses)
    {
        $items = [];

        $actualItems = $this->addresses()->select('id')->get();
        $actualIds = $actualItems->map(function($item) {
            return $item->id;
        });

        $editedIds = [];
        foreach ($addresses as $address) {

            if ($address instanceof Address) {

                $items[] = $address;

                if ($address->id) {
                    $editedIds[] = $address->id;
                }

            } elseif (is_array($address)) {

                $object = new Address($address);
                $items[] = $object;

                if ($object->id) {
                    $editedIds[] = $object->id;
                }
            }
        }

        DB::transaction(function() use ($items, $actualIds, $editedIds) {
            $this->addresses()->saveMany($items);

            $toDeleteIds = array_diff($actualIds, $editedIds);
            $this->addresses()->whereIn('id', $toDeleteIds)->delete();
        });

        return $this;
    }

}
