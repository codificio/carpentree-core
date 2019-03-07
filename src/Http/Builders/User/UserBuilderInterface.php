<?php

namespace Carpentree\Core\Http\Builders\User;

use Carpentree\Core\Http\Builders\BuilderInterface;

interface UserBuilderInterface extends BuilderInterface
{
    public function withRoles(array $data) : BuilderInterface;
}
