<?php

namespace Carpentree\Core\Models\Address;

use Carpentree\Core\Models\Address;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Type extends Model implements TranslatableContract
{
    use Translatable;

    public $timestamps = false;

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
        return self::getByName('home');
    }

    /**
     * @return mixed
     */
    public static function getBusinessType()
    {
        return self::getByName('business');
    }

    /**
     * @return mixed
     */
    public static function getShippingType()
    {
        return self::getByName('shipping');
    }

    /**
     * @return mixed
     */
    public static function getBillingType()
    {
        return self::getByName('billing');
    }

    /**
     * @param $name
     * @return mixed
     */
    protected static function getByName($name)
    {
        $item = self::where('name', $name)->first();
        if (!$item) {
            throw new NotFoundHttpException(__(":class with name :name does not exist", [
                'class' => self::class,
                'name' => $name
            ]));
        }

        return $item;
    }
}
