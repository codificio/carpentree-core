<?php

namespace Carpentree\Core\Models\Address;

use Carpentree\Core\Models\Address;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use Translatable;

    public $translationModel = Address\Type\Translation::class;

    protected $table = 'address_types';

    public $fillable = [
        'name',
    ];

    public $translatedAttributes = [
        'label',
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
