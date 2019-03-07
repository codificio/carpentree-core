<?php

namespace Carpentree\Core\Http\Builders;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface BuilderInterface.
 * Build a model from Http Request.
 *
 * @package Carpentree\Core\Http\Builders
 */
interface BuilderInterface
{
    public function __construct();

    public function init(Model $model = null) : BuilderInterface;

    public function create(array $attributes) : BuilderInterface;

    public function withCategories(array $data) : BuilderInterface;

    public function withMeta(array $data) : BuilderInterface;

    public function withMedia(array $data) : BuilderInterface;

    public function build();
}
