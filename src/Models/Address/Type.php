<?php

namespace Carpentree\Core\Models\Address;

use Carpentree\Core\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'address_types';

    protected $fillable = [
        'name'
    ];

    /**
     * Get the addresses belonging to this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class, 'type_id');
    }

}
