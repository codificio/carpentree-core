<?php

namespace Carpentree\Core\Exceptions;

use InvalidArgumentException;

class ModelHasNotCategories extends InvalidArgumentException
{
    public static function create($class)
    {
        return new static(__("Class $class doesn't have categories"));
    }
}
