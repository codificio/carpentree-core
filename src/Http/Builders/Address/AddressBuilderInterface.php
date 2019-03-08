<?php

namespace Carpentree\Core\Http\Builders\Address;

use Carpentree\Core\Http\Builders\BuilderInterface;

interface AddressBuilderInterface extends BuilderInterface
{
    public function withUser(array $data) : BuilderInterface;

    public function withAddressType(array $data) : BuilderInterface;
}
