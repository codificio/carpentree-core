<?php

namespace Carpentree\Core\Events;

use Carpentree\Core\Models\User;

class UserDeleted
{
    /**
     * The deleted user ID.
     *
     * @var string
     */
    public $userId;

    /**
     * Create a new event instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->userId = $user->id;
    }
}
