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

    /**
     * @return mixed
     */
    public static function getHomeType()
    {
        return self::where('name', 'home')->first();
    }

    /**
     * @return mixed
     */
    public static function getBusinessType()
    {
        return self::where('name', 'business')->first();
    }

    /**
     * @return mixed
     */
    public static function getShippingType()
    {
        return self::where('name', 'shipping')->first();
    }

    /**
     * @return mixed
     */
    public static function getBillingType()
    {
        return self::where('name', 'billing')->first();
    }
}
