<?php

namespace Carpentree\Core\Events;

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
     * @param $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
}
