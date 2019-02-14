<?php

namespace Carpentree\Core\Models\Category;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $timestamps = false;

    protected $table = 'category_translations';

    protected $fillable = [
        'slug',
        'name',
        'description'
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'slug' => 'string'
    ];

}
