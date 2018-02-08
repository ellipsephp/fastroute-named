<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use RuntimeException;

class PlaceholderFormatException extends RuntimeException implements FastRouteExceptionInterface
{
    public function __construct(string $value, string $name, string $placeholder, string $format)
    {
        $template = "The value '%s' does not match the format expected by the '%s' placeholder of the '%s' route ('%s')";

        $msg = sprintf($template, $value, $placeholder, $name, $format);

        parent::__construct($msg);
    }
}
