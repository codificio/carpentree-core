<?php

namespace Carpentree\Core\Models;

use Carpentree\Core\Events\UserDeleted;
use Carpentree\Core\Notifications\ResetPassword;
use Carpentree\Core\Scout\Searchable;
use Carpentree\Core\Traits\HasAddresses;
use Carpentree\Core\Traits\HasMeta;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Carpentree\Core\Traits\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailInterface;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmailInterface
{
    use Notifiable,
        HasApiTokens,
        HasRoles,
        MustVerifyEmail,
        CanResetPassword,
        SoftDeletes,
        Searchable,
        HasMeta,
        HasAddresses;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleted' => UserDeleted::class,
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [
            'id' => $this->id, // TNTSearch needs id
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'roles' => $this->getRoleNames(),
            'meta' => $this->meta->pluck('value')
        ];

        return $array;
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the social accounts linked.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function linkedSocialAccounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }

    /**
     * Send password reset notification.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token, $this->email));
    }

    /**
     * Check if current user has access to the backend.
     *
     * @return bool
     */
    public function hasBackendAccess()
    {
        return $this->hasAnyRole(config('carpentree.core.backend_roles'));
    }

    /**
     * Determine whether the user is Super Admin (total privileges).
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('Super Admin');
    }
}
