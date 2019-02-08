<?php

namespace Carpentree\Core\Models\User;

use Carpentree\Core\Models\User;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'user_meta';

    protected $fillable = [
        'user_id',
        'key',
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
