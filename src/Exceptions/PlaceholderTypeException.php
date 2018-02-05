<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use TypeError;

class PlaceholderTypeException extends TypeError implements FastRouteExceptionInterface
{
    public function __construct($value)
    {
        $template = "Trying to use a value of type %s as route placeholder - scalar expected";

        $type = is_object($value) ? get_class($value) : gettype($value);

        $msg = sprintf($template, $type);

        parent::__construct($msg);
    }
}
