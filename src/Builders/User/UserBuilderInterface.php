<?php

namespace Carpentree\Core\Builders\User;

use Carpentree\Core\Builders\BuilderInterface;

interface UserBuilderInterface extends BuilderInterface
{
    public function withRoles(array $data) : BuilderInterface;

    public function withAddresses(array $data) : BuilderInterface;
}
