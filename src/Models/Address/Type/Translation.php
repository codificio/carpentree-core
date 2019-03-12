<?php

namespace Carpentree\Core\Models\Address\Type;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $timestamps = false;

    protected $table = 'address_type_translations';

    protected $fillable = [
        'label',
    ];
}
