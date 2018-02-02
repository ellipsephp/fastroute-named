<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteParser;
use FastRoute\DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;

class GroupCountBasedRouter extends Router
{
    /**
     * Set up a default fastroute router with the given mapper. Use group count
     * based fastroute collector and dispatcher.
     *
     * @param callable $mapper
     */
    public function __construct(callable $mapper)
    {
        $collector = new RouteCollector(
            new RouteParser\Std, new DataGenerator\GroupCountBased
        );

        $factory = function ($data) {

            return new Dispatcher\GroupCountBased($data);

        };

        parent::__construct($collector, $factory, $mapper);
    }
}
