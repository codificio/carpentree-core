<?php

namespace Carpentree\Core\Builders\User;

use Carpentree\Core\Builders\BaseBuilder;
use Carpentree\Core\Builders\BuilderInterface;
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
        $class = config('carpentree.core.user_class', User::class);
        return $class;
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withRoles(array $data)
    {
        try {
            $this->model = $this->model->syncRoles($data);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }
}
