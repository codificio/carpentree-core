<?php

namespace Carpentree\Core\Exceptions;

use InvalidArgumentException;

class ModelIsNotSearchable extends InvalidArgumentException
{
    public static function create($class)
    {
        return new static(__("Class :class is not searchable", [
            'class' => $class
        ]));
    }
}
