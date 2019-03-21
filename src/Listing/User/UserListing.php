<?php

namespace Carpentree\Core\Listing\User;

use Carpentree\Core\Listing\BaseListing;
use Carpentree\Core\Models\User;

class UserListing extends BaseListing implements UserListingInterface
{
    public function __construct()
    {
        $this->model = User::class;
        $temp = new User();
        $this->table = $temp->getTable();
    }
}
