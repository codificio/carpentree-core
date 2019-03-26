<?php

namespace Carpentree\Core\Builders;

use Carpentree\Core\Exceptions\ModelHasNotAddresses;
use Carpentree\Core\Exceptions\ModelHasNotCategories;
use Carpentree\Core\Traits\Categorizable;
use Carpentree\Core\Traits\HasAddresses;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class BaseBuilder implements BuilderInterface
{
    /** @var Model $model */
    protected $model;

    /** @var string $class */
    protected $class;

    /**
     * BaseBuilder constructor.
     */
    public function __construct()
    {
        $this->class = $this->getClass();
    }

    /**
     * @param Model|null $model
     * @return $this
     * @throws Exception
     */
    public function init(Model $model = null)
    {
        if ($model instanceof $this->class) {
            // Existing object
            $this->model = $model;
        } elseif (is_null($model)) {
            // New object
            $this->model = new $this->class();
        } else {
            throw new Exception("Wrong class initialized in builder");
        }

        DB::beginTransaction();

        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     * @throws Exception
     */
    public function fill(array $attributes)
    {
        try {
            $this->model = $this->model->fill($attributes);
            if (!$this->model->save()) {
                throw new Exception("There was an error during model saving.");
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $this;
    }


    /**
     * @deprecated 0.3.14.3
     * @param array $attributes
     * @return $this
     * @throws Exception
     */
    public function create(array $attributes)
    {
        return $this->fill($attributes);
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withCategories(array $data)
    {
        try {
            if (!in_array(Categorizable::class, class_uses($this->getClass()))) {
                throw ModelHasNotCategories::create($this->getClass());
            }

            $this->model = $this->model->syncCategories($data);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }


        return $this;
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withMeta(array $data)
    {
        try {
            $this->model->syncMeta($data);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withMedia(array $data)
    {
        try {

            $data = collect($data);

            if ($data->count() > 0) {

                foreach ($data as $tag => $ids) {
                    $this->model->syncMedia($ids, $tag);
                }

            } else {

                $dataByTag = $this->model->getAllMediaByTag();
                foreach ($dataByTag as $tag => $files) {
                    $this->model->detachMediaTags($tag);
                }

            }

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withAddresses(array $data)
    {
        try {

            if (!in_array(HasAddresses::class, class_uses($this->getClass()))) {
                throw ModelHasNotAddresses::create($this->getClass());
            }

            $this->model->syncAddresses($data);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }

    /**
     * @return bool|Model
     * @throws Exception
     */
    public function build()
    {
        if (!$this->model->save()) {
            DB::rollBack();
            throw new Exception("There was an error during model saving.");
        }

        DB::commit();

        return $this->model;
    }

    /**
     * @return mixed
     */
    abstract protected function getClass();
}
