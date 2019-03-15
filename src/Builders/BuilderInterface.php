<?php

namespace Carpentree\Core\Builders;

use Illuminate\Database\Eloquent\Model;

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
     * @return BuilderInterface
     */
    public function init(Model $model = null) : BuilderInterface;

    /**
     * @param array $attributes
     * @return BuilderInterface
     */
    public function fill(array $attributes) : BuilderInterface;

    /**
     * @deprecated 0.3.14.3
     */
    public function create(array $attributes) : BuilderInterface;

    /**
     * @param array $data
     * @return BuilderInterface
     */
    public function withCategories(array $data) : BuilderInterface;

    /**
     * @param array $data
     * @return BuilderInterface
     */
    public function withMeta(array $data) : BuilderInterface;

    /**
     * @param array $data
     * @return BuilderInterface
     */
    public function withMedia(array $data) : BuilderInterface;

    /**
     * @param array $data
     * @return BuilderInterface
     */
    public function withAddresses(array $data) : BuilderInterface;

    /**
     * @return mixed
     */
    public function build();
}
