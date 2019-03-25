<?php

namespace Carpentree\Core\Builders\Address;

use Carpentree\Core\Builders\BaseBuilder;
use Carpentree\Core\Builders\BuilderInterface;
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
     * @param User|integer $data
     * @return $this
     * @throws Exception
     */
    public function withUser($data)
    {
        try {

            if ($data instanceof User) {
                $user = $data;
            } else {
                // $data is id
                $user = User::findOrFail($data);
            }

            $this->model = $this->model->user()->associate($user);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }

    /**
     * @param $id
     * @return $this
     * @throws Exception
     */
    public function withAddressType($id)
    {
        try {
            $type = Address\Type::findOrFail($id);
            $this->model = $this->model->type()->associate($type);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }
}
