<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteParser;

use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

class RouteMap
{
    /**
     * The list of prefixes to prepend to the route names.
     *
     * @var array
     */
    private $names;

    /**
     * The list of prefixes to prepend to the route patterns.
     *
     * @var array
     */
    private $patterns;

    /**
     * The associative array of route name => route pattern pairs.
     *
     * @var array
     */
    private $name2pattern;

    /**
     * The fastroute route parser
     *
     * @var \FastRoute\RouteParser\Std
     */
    private $parser;

    /**
     * Set up a route map.
     */
    public function __construct()
    {
        $this->names = [];
        $this->patterns = [];
        $this->name2pattern = [];
        $this->parser = new RouteParser\Std;
    }

    /**
     * Return a new RoutePattern from the route pattern associated to the given
     * route name.
     *
     * @param string $name
     * @return \Ellipse\FastRoute\RoutePattern
     * @throws \Ellipse\FastRoute\Exceptions\RouteNameNotMappedException
     */
    public function pattern(string $name): RoutePattern
    {
        if (array_key_exists($name, $this->name2pattern)) {

            $pattern = $this->name2pattern[$name];

            $signatures = $this->parser->parse($pattern);

            return new RoutePattern($name, $signatures);

        }

        throw new RouteNameNotMappedException($name);
    }

    /**
     * Associate the given route name with the given route pattern when not
     * empty. Prefix it with the current prefix.
     *
     * @param string $name
     * @param string $route
     * @return void
     * @throws \Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException
     */
    public function associate(string $name, string $route): void
    {
        if ($name != '') {

            $prefixed_name = $this->prefixedName($name);
            $prefixed_pattern = $this->prefixedPattern($route);

            if (array_key_exists($prefixed_name, $this->name2pattern)) {

                throw new RouteNameAlreadyMappedException($prefixed_name);

            }

            $this->name2pattern[$prefixed_name] = $prefixed_pattern;

        }
    }

    /**
     * Return the given route name prefixed with the current route name prefixes
     * (spaced by a dot).
     *
     * @param string $name
     * @return string
     */
    private function prefixedName(string $name): string
    {
        $parts = array_merge($this->names, [$name]);

        return implode('.', array_filter($parts));
    }

    /**
     * Return the given route pattern prefixed with the current route pattern
     * prefixes.
     *
     * @param string $pattern
     * @return string
     */
    private function prefixedPattern(string $pattern): string
    {
        $parts = array_merge($this->patterns, [$pattern]);

        return implode('', array_filter($parts));
    }

    /**
     * Add a route name prefix to the current route name prefixes (spaced by a
     * dot).
     *
     * @param string $name
     * @return void
     */
    public function addNamePrefix(string $prefix): void
    {
        $this->names[] = $prefix;
    }

    /**
     * Remove the last route name prefix from the current route name prefixes.
     *
     * @return void
     */
    public function removeNamePrefix(): void
    {
        array_pop($this->names);
    }

    /**
     * Add a route pattern prefix to the current route pattern prefixes.
     *
     * @param string $name
     * @return void
     */
    public function addPatternPrefix(string $prefix): void
    {
        $this->patterns[] = $prefix;
    }

    /**
     * Remove the last route pattern prefix from the current route pattern
     * prefixes.
     *
     * @return void
     */
    public function removePatternPrefix(): void
    {
        array_pop($this->patterns);
    }
}
