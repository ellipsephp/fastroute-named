<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use Ellipse\FastRoute\Exceptions\PlaceholderCountException;

class RoutePattern
{
    /**
     * The route name.
     *
     * @var string
     */
    private $name;

    /**
     * The route allowed signatures.
     *
     * @var array
     */
    private $signatures;

    /**
     * Set up a route pattern with the given route name and allowed signatures.
     *
     * @param string    $name
     * @param array     $signatures
     */
    public function __construct(string $name, array $signatures)
    {
        $this->name = $name;
        $this->signatures = $signatures;
    }

    /**
     * Return an url for this route pattern using the given placeholders, query
     * string and fragment.
     *
     * @param array     $placeholders
     * @param array     $query
     * @param string    $fragment
     * @return \Ellipse\FastRoute\Url
     * @throws \Ellipse\FastRoute\Exceptions\PlaceholderCountException
     */
    public function url(array $placeholders = [], array $query = [], string $fragment = ''): Url
    {
        $given = count($placeholders);
        $accepted = [];

        foreach ($this->signatures as $signature) {

            $nb = count(array_filter($signature, 'is_array'));

            if ($given == $nb) {

                return new Url(new UrlPath($this->name, $signature, $placeholders), $query, $fragment);

            }

            $accepted[] = $nb;

        }

        throw new PlaceholderCountException($this->name, $accepted, $given);
    }
}
