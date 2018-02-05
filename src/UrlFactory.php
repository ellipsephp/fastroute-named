<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

class UrlFactory
{
    /**
     * The named route collector.
     *
     * @var \Ellipse\FastRoute\NamedRouteCollector
     */
    private $collector;

    /**
     * Set up an url factory with the given named route collector.
     *
     * @param \Ellipse\FastRoute\NamedRouteCollector $collector
     */
    public function __construct(NamedRouteCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Return an url from the route signature matching the given route name and
     * placeholders, enventually appending the given query string and fragment.
     *
     * @param string    $name
     * @param array     $placeholders
     * @param array     $query
     * @param string    $fragment
     * @return string
     */
    public function __invoke(string $name, array $placeholders = [], array $query = [], string $fragment = ''): string
    {
        return $this->collector->pattern($name)->url($placeholders, $query, $fragment)->value();
    }
}
