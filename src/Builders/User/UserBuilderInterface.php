<?php

namespace Carpentree\Core\Builders\User;

use Carpentree\Core\Builders\BuilderInterface;
use Exception;

interface UserBuilderInterface extends BuilderInterface
{
    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withRoles(array $data);
}
