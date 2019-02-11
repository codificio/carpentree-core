<?php

namespace Carpentree\Core\Repositories;

use Carpentree\Core\Events\UserDeleted;
use Carpentree\Core\Models\User;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return User::class;
    }

    /**
     * Delete a entity in repository by id
     *
     * @param $id
     *
     * @return int
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function delete($id)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model = $this->find($id);
        $originalModel = clone $model;

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        $deleted = $model->delete();

        event(new UserDeleted($id));
        event(new RepositoryEntityDeleted($this, $originalModel));

        return $deleted;
    }
}
