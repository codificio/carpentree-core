<?php

namespace Carpentree\Core\Models\MetaField;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $timestamps = false;

    protected $table = 'meta_fields_translations';

    protected $fillable = [
        'value'
    ];

}
