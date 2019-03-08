<?php

namespace Carpentree\Core\Http\Builders\Address;

use Carpentree\Core\Http\Builders\BaseBuilder;
use Carpentree\Core\Http\Builders\BuilderInterface;
use Carpentree\Core\Models\Address;
use Carpentree\Core\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class AddressBuilder extends BaseBuilder implements AddressBuilderInterface
{

    /**
     * @return mixed
     */
    protected function getClass()
    {
        return Address::class;
    }


    /**
     * @param array $data
     * @return BuilderInterface
     * @throws Exception
     */
    public function withUser(array $data): BuilderInterface
    {
        try {
            $user = User::findOrFail($data['id']);
            $this->model = $this->model->user()->associate($user);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }

    /**
     * @param array $data
     * @return BuilderInterface
     * @throws Exception
     */
    public function withAddressType(array $data): BuilderInterface
    {
        try {
            $type = Address\Type::findOrFail($data['id']);
            $this->model = $this->model->type()->associate($type);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }
}
