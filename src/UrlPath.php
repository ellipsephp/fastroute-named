<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use Ellipse\FastRoute\Exceptions\WrongParameterFormatException;

class UrlPath
{
    /**
     * The route name.
     *
     * @var string
     */
    private $name;

    /**
     * The route signature.
     *
     * @var array
     */
    private $signature;

    /**
     * The placeholders replacing the variable parts of the signature.
     *
     * @var array
     */
    private $placeholders;

    /**
     * Set up an url path with the given route name, route signature and
     * placeholders.
     *
     * @param string    $name
     * @param array     $signature
     * @param array     $placeholders
     */
    public function __construct(string $name, array $signature, array $placeholders)
    {
        $this->name = $name;
        $this->signature = $signature;
        $this->placeholders = $placeholders;
    }

    /**
     * Return the head and tail of the given list.
     *
     * @param array $list
     * @return array
     */
    private function split(array $list): array
    {
        return [array_shift($list), $list];
    }

    /**
     * Return the url path value by recursively merging the parts of the route
     * signature, replacing the variable ones with the placeholders.
     *
     * @return string
     * @throws \Ellipse\FastRoute\Exceptions\WrongParameterFormatException
     */
    public function value(): string
    {
        if (count($this->signature) > 0) {

            [$part, $signature] = $this->split($this->signature);

            if (is_array($part)) {

                [$placeholder, $placeholders] = $this->split($this->placeholders);

                if (preg_match('~^' . $part[1] . '$~', (string) $placeholder) !== 0) {

                    return $placeholder . (new UrlPath($this->name, $signature, $placeholders))->value();

                }

                throw new WrongParameterFormatException($placeholder, $this->name, $part[0], $part[1]);

            }

            return $part . (new UrlPath($this->name, $signature, $this->placeholders))->value();

        }

        return '';
    }
}
