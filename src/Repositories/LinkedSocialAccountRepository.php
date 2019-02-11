<?php

namespace Carpentree\Core\Repositories;

use Carpentree\Core\Models\LinkedSocialAccount;
use Prettus\Repository\Eloquent\BaseRepository;

class LinkedSocialAccountRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return LinkedSocialAccount::class;
    }
}
