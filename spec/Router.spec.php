<?php declare(strict_types=1);

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

use Ellipse\FastRoute\Router;
use Ellipse\FastRoute\UrlFactory;
use Ellipse\FastRoute\NamedRouteCollector;
use Ellipse\FastRoute\Exceptions\DispatcherTypeException;

describe('Router', function () {

    beforeEach(function () {

        $this->collector = mock(RouteCollector::class);
        $this->factory = stub();
        $this->mapper = stub();

        $this->router = new Router($this->collector->get(), $this->factory, $this->mapper);

    });

    describe('->dispatcher()', function () {

        context('when the factory returns an instance of Dispatcher', function () {

            it('should populate the route collector with the mapper and return the dispatcher', function () {

                $named = new NamedRouteCollector($this->collector->get());
                $dispatcher = mock(Dispatcher::class)->get();

                $this->collector->getData->returns(['data']);

                $this->factory->with(['data'])->returns($dispatcher);

                $test = $this->router->dispatcher();

                expect($test)->toBe($dispatcher);

                $this->mapper->calledWith($named);

            });

            it('the collector should be populated only one time', function () {

                $named = new NamedRouteCollector($this->collector->get());
                $dispatcher = mock(Dispatcher::class)->get();

                $this->factory->returns($dispatcher);

                $this->router->dispatcher();
                $this->router->dispatcher();

                $this->mapper->once()->calledWith($named);

            });

        });

        context('when the factory does not return an instance of Dispatcher', function () {

            it('should throw a DispatcherTypeException', function () {

                $this->collector->getData->returns(['data']);

                $this->factory->with(['data'])->returns('dispatcher');

                $test = function () { $this->router->dispatcher(); };

                $exception = new DispatcherTypeException('dispatcher');

                expect($test)->toThrow($exception);

            });

        });

    });

    describe('->generator()', function () {

        it('should populate the route collector with the mapper and return a new UrlFactory wrapped around it', function () {

            $named = new NamedRouteCollector($this->collector->get());

            $test = $this->router->generator();

            $factory = new UrlFactory($named);

            expect($test)->toEqual($factory);

            $this->mapper->calledWith($named);

        });

        it('the collector should be populated only one time', function () {

            $named = new NamedRouteCollector($this->collector->get());

            $this->router->generator();
            $this->router->generator();

            $this->mapper->once()->calledWith($named);

        });

    });

});
