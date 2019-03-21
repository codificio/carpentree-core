<?php

namespace Carpentree\Core\Listing\User;

use Carpentree\Core\Listing\AlgoliaBaseListing;
use Carpentree\Core\Models\User;

class UserListing extends AlgoliaBaseListing implements UserListingInterface
{
    public function __construct()
    {
        $this->model = User::class;
        $temp = new User();
        $this->table = $temp->getTable();
    }
}
