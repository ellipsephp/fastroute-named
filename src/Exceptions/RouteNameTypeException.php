<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use TypeError;

class RouteNameTypeException extends TypeError implements FastRouteExceptionInterface
{
    public function __construct($value)
    {
        $template = "Trying to use a value of type %s as route name - string expected";

        $type = is_object($value) ? get_class($value) : gettype($value);

        $msg = sprintf($template, $type);

        parent::__construct($msg);
    }
}
