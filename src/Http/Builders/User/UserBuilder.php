<?php

namespace Carpentree\Core\Http\Builders\User;

use Carpentree\Core\Http\Builders\BaseBuilder;
use Carpentree\Core\Http\Builders\BuilderInterface;
use Carpentree\Core\Models\Address;
use Carpentree\Core\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserBuilder extends BaseBuilder implements UserBuilderInterface
{

    /**
     * @return mixed
     */
    protected function getClass()
    {
        return User::class;
    }

    /**
     * @param array $data
     * @return BuilderInterface
     * @throws Exception
     */
    public function withRoles(array $data): BuilderInterface
    {
        try {
            $this->model = $this->model->syncRoles($data);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }

    public function withAddresses(array $data) : BuilderInterface
    {
        try {

            foreach ($data as $address) {
                if ($address instanceof Address) {
                    $this->model->addresses()->save($address);
                } elseif (is_array($address)) {
                    // TODO Create address from array
                }
            }

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }
}
