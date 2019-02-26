<?php

namespace Carpentree\Core\Models;

use Carpentree\Core\Models\MetaField\Translation;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Dimsav\Translatable\Translatable;

class MetaField extends Model
{
    use NodeTrait;
    use Translatable;

    public $translationModel = Translation::class;

    public $fillable = [
        'key'
    ];

    public $translatedAttributes = [
        'value'
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
