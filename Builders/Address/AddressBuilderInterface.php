<?php

namespace Carpentree\Core\Builders\Address;

use Carpentree\Core\Builders\BuilderInterface;

interface AddressBuilderInterface extends BuilderInterface
{
    public function withUser($data) : BuilderInterface;

    public function withAddressType($id) : BuilderInterface;
}
