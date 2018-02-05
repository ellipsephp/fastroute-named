<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

use Ellipse\FastRoute\Exceptions\DispatcherTypeException;

class Router
{
    /**
     * The named route collector wrapped around the fastroute collector.
     *
     * @var \Ellipse\Router\NamedRouteCollector
     */
    private $collector;

    /**
     * The fastroute dispatcher factory.
     *
     * @var \FastRoute\Dispatcher
     */
    private $factory;

    /**
     * The route mapper.
     *
     * @var callable
     */
    private $mapper;

    /**
     * Whether the route collector has already been populated with the mapper.
     *
     * @var bool
     */
    private $mapped;

    /**
     * Set up a fastroute router with the given fastroute collector, fastroute
     * dispatcher factory and route mapper.
     *
     * @param \FastRoute\RouteCollector $collector
     * @param callable                  $factory
     * @param callable                  $mapper
     */
    public function __construct(RouteCollector $collector, callable $factory, callable $mapper)
    {
        $this->collector = new NamedRouteCollector($collector);
        $this->factory = $factory;
        $this->mapper = $mapper;
        $this->mapped = false;
    }

    /**
     * Return a new dispatcher by proxying the dispatcher factory with the
     * populated route collector data.
     *
     * @return \FastRoute\Dispatcher
     * @throws \Ellipser\FastRoute\Exceptions\DispatcherTypeException
     */
    public function dispatcher(): Dispatcher
    {
        $this->mapRoutes();

        $data = $this->collector->getData();

        $dispatcher = ($this->factory)($data);

        if ($dispatcher instanceof Dispatcher) {

            return $dispatcher;

        }

        throw new DispatcherTypeException($dispatcher);
    }

    /**
     * Return a new UrlFactory with the populated route collector.
     *
     * @return \Ellipse\Router\UrlFactory
     */
    public function generator(): UrlFactory
    {
        $this->mapRoutes();

        return new UrlFactory($this->collector);
    }

    /**
     * Populate the named route collector the first time it is called.
     *
     * @return void
     */
    private function mapRoutes(): void
    {
        if (! $this->mapped) {

            ($this->mapper)($this->collector);

            $this->mapped = true;

        }
    }
}
