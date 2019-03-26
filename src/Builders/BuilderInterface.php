<?php

namespace Carpentree\Core\Builders;

use Illuminate\Database\Eloquent\Model;
use Exception;

/**
 * Interface BuilderInterface.
 * Build a model from Http Request.
 *
 * @package Carpentree\Core\Http\Builders
 */
interface BuilderInterface
{
    /**
     * BuilderInterface constructor.
     */
    public function __construct();

    /**
     * @param Model|null $model
     * @return $this
     * @throws Exception
     */
    public function init(Model $model = null);

    /**
     * @param array $attributes
     * @return $this
     * @throws Exception
     */
    public function fill(array $attributes);

    /**
     * @deprecated 0.3.14.3
     * @param array $attributes
     * @return $this
     * @throws Exception
     */
    public function create(array $attributes);

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withCategories(array $data);

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withMeta(array $data);

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withMedia(array $data);

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    public function withAddresses(array $data);

    /**
     * @return bool|Model
     * @throws Exception
     */
    public function build();
}
