<?php

namespace Carpentree\Core\Models;

use Carpentree\Core\Models\Address\Type;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'type_id',
        'user_id',
        'full_name',
        'address_line',
        'country',
        'city',
        'state',
        'postal_code',
        'phone_number'
    ];

    /**
     * Get the type of the address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /**
     * Get the user that own this address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
