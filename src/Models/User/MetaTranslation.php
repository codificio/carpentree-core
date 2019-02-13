<?php

namespace Carpentree\Core\Models\User;

use Illuminate\Database\Eloquent\Model;

class MetaTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'user_meta_translations';

    protected $fillable = [
        'value'
    ];

}
