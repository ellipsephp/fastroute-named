<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use TypeError;

class PlaceholderTypeException extends TypeError implements FastRouteExceptionInterface
{
    public function __construct($value, string $name, string $placeholder)
    {
        $template = "Trying to use a value of type %s for the '%s' placeholder of the '%s' route - scalar expected";

        $type = is_object($value) ? get_class($value) : gettype($value);

        $msg = sprintf($template, $type, $placeholder, $name);

        parent::__construct($msg);
    }
}
