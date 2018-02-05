<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use RuntimeException;

class WrongNumberOfParametersException extends RuntimeException implements FastRouteExceptionInterface
{
    public function __construct(string $name, array $allowed, int $given)
    {
        $template = "The route '%s' %s, %s given";

        $min = min($allowed);
        $max = max($allowed);

        $expected_str = $this->format($min, $max);

        $msg = sprintf($template, $name, $expected_str, $given);

        parent::__construct($msg);
    }

    /**
     * Return a formatted expectation string for the given min and max
     * parameters.
     *
     * @param int $min
     * @param int $max
     * @return string
     */
    private function format(int $min, int $max): string
    {
        if ($min == $max) {

            if ($min == 0) {

                return 'don\'t require any parameter';

            }

            elseif ($min == 1) {

                return 'require exactly 1 parameter';

            } else {

                return implode(' ', ['require', 'exactly', $min, 'parameters']);

            }

        } else {

            return implode(' ', ['require', 'between', $min, 'and', $max, 'parameters']);

        }
    }
}
