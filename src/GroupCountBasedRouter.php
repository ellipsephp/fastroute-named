<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteParser;
use FastRoute\DataGenerator;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

class GroupCountBasedRouter extends Router
{
    /**
     * Set up a group count based fastroute router with the given mapper.
     *
     * @param callable $mapper
     */
    public function __construct(callable $mapper)
    {
        $parser = new RouteParser\Std;

        $generator = new DataGenerator\GroupCountBased;

        $collector = new RouteCollector($parser, $generator);

        $factory = function ($data) {

            return new Dispatcher\GroupCountBased($data);

        };

        parent::__construct($collector, $factory, $mapper);
    }
}
