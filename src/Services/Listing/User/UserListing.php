<?php

namespace Carpentree\Core\Services\Listing\User;

use Carpentree\Core\Models\User;
use Carpentree\Core\Services\Listing\BaseListing;

class UserListing extends BaseListing implements UserListingInterface
{
    public function __construct()
    {
        $this->model = User::class;
        $temp = new User();
        $this->table = $temp->getTable();
    }
}
