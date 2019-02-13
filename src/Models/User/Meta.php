<?php

namespace Carpentree\Core\Models\User;

use Carpentree\Core\Models\User;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use Translatable;

    public $translationModel = MetaTranslation::class;

    protected $table = 'user_meta';

    protected $fillable = [
        'user_id',
        'key'
    ];

    public $translatedAttributes = [
        'value'
    ];

    /**
     * Get the user that own this meta information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
