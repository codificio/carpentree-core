<?php

namespace Carpentree\Core\Models\User\Meta;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $timestamps = false;

    protected $table = 'user_meta_translations';

    protected $fillable = [
        'value'
    ];

}
