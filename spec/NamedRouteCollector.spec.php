<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\partialMock;
use function Eloquent\Phony\Kahlan\anInstanceOf;

use FastRoute\RouteCollector;
use FastRoute\RouteParser;
use FastRoute\DataGenerator;

use Ellipse\FastRoute\RouteMap;
use Ellipse\FastRoute\RoutePattern;
use Ellipse\FastRoute\NamedRouteCollector;
use Ellipse\FastRoute\Exceptions\RouteNameTypeException;

describe('NamedRouteCollector', function () {

    beforeEach(function () {

        $this->map = mock(RouteMap::class);

        allow(RouteMap::class)->toBe($this->map->get());

        $this->delegate = mock(RouteCollector::class);

        $this->collector = new NamedRouteCollector($this->delegate->get());

    });

    describe('->pattern()', function () {

        it('should proxy the map ->pattern() method', function () {

            $pattern = mock(RoutePattern::class)->get();

            $this->map->pattern->with('name')->returns($pattern);

            $test = $this->collector->pattern('name');

            expect($test)->toBe($pattern);

        });

    });

    describe('->addRoute()', function () {

        context('when no route name is given', function () {

            it('should proxy the delegate', function () {

                $this->collector->addRoute('GET', '/pattern', 'handler');

                $this->delegate->addRoute->calledWith('GET', '/pattern', 'handler');

            });

            it('should proxy the map ->associate() method with an empty route name', function () {

                $this->collector->addRoute('GET', '/pattern', 'handler');

                $this->map->associate->calledWith('', '/pattern');

            });

        });

        context('when a route name is given', function () {

            context('when the given route name is a string', function () {

                it('should proxy the delegate', function () {

                    $this->collector->addRoute('name', 'GET', '/pattern', 'handler');

                    $this->delegate->addRoute->calledWith('GET', '/pattern', 'handler');

                });

                it('should proxy the map ->associate() method', function () {

                    $this->collector->addRoute('name', 'GET', '/pattern', 'handler');

                    $this->map->associate->calledWith('name', '/pattern');

                });

            });

            context('when the given route name is not a string', function () {

                it('should throw a RouteNameTypeException', function () {

                    $test = function () {

                        $this->collector->addRoute(1, 'GET', '/pattern', 'handler');

                    };

                    $exception = new RouteNameTypeException(1);

                    expect($test)->toThrow($exception);

                });

            });

        });

    });

    describe('->addGroup()', function () {

        beforeEach(function () {

            $this->concrete = new NamedRouteCollector(
                new RouteCollector(new RouteParser\Std, new DataGenerator\GroupCountBased)
            );

            $this->callback = stub();

        });

        context('when no route name prefix is given', function () {

            it('should proxy the delegate', function () {

                $this->collector->addGroup('/route_prefix', $this->callback);

                $this->delegate->addGroup->calledWith('/route_prefix', anInstanceOf(Closure::class));

            });

            it('should call the given callback with the named route collector', function () {

                $this->concrete->addGroup('/route_prefix', $this->callback);

                $this->callback->calledWith($this->concrete);

            });

            it('should call the map ->addNamePrefix() with an empty route name prefix', function () {

                $this->concrete->addGroup('/route_prefix', $this->callback);

                $this->map->addNamePrefix->calledWith('');

            });

            it('should call the map ->addPatternPrefix() with the given route pattern prefix', function () {

                $this->concrete->addGroup('/route_prefix', $this->callback);

                $this->map->addPatternPrefix->calledWith('/route_prefix');

            });

            it('should call the map ->removeNamePrefix()', function () {

                $this->concrete->addGroup('/route_prefix', $this->callback);

                $this->map->removeNamePrefix->called();

            });

            it('should call the map ->removePatternPrefix()', function () {

                $this->concrete->addGroup('/route_prefix', $this->callback);

                $this->map->removePatternPrefix->called();

            });

        });

        context('when a route name prefix is given', function () {

            context('when the given route name prefix is a string', function () {

                it('should proxy the delegate', function () {

                    $this->collector->addGroup('name_prefix', '/route_prefix', $this->callback);

                    $this->delegate->addGroup->calledWith('/route_prefix', anInstanceOf(Closure::class));

                });

                it('should call the given callback with the named route collector', function () {

                    $this->concrete->addGroup('name_prefix', '/route_prefix', $this->callback);

                    $this->callback->calledWith($this->concrete);

                });

                it('should call the map ->addNamePrefix() with the given route name prefix', function () {

                    $this->concrete->addGroup('name_prefix', '/route_prefix', $this->callback);

                    $this->map->addNamePrefix->calledWith('name_prefix');

                });

                it('should call the map ->addPatternPrefix() with the given route pattern prefix', function () {

                    $this->concrete->addGroup('name_prefix', '/route_prefix', $this->callback);

                    $this->map->addPatternPrefix->calledWith('/route_prefix');

                });

                it('should call the map ->removeNamePrefix()', function () {

                    $this->concrete->addGroup('name_prefix', '/route_prefix', $this->callback);

                    $this->map->removeNamePrefix->called();

                });

                it('should call the map ->removePatternPrefix()', function () {

                    $this->concrete->addGroup('name_prefix', '/route_prefix', $this->callback);

                    $this->map->removePatternPrefix->called();

                });

            });

            context('when the given route name prefix is not a string', function () {

                it('should throw a RouteNameTypeException', function () {

                    $test = function () {

                        $this->concrete->addGroup(1, '/route_prefix', $this->callback);

                    };

                    $exception = new RouteNameTypeException(1);

                    expect($test)->toThrow($exception);

                });

            });

        });

    });

    describe('->getData()', function () {

        it('should proxy the delegate', function () {

            $data = ['route1' => 'data1', 'route2' => 'data2'];

            $this->delegate->getData->returns($data);

            $test = $this->collector->getData();

            expect($test)->toEqual($data);

        });

    });

    describe('shortcuts', function () {

        beforeEach(function () {

            $this->collector = partialMock(NamedRouteCollector::class, [
                $this->delegate->get(),
            ]);

        });

        describe('->get()', function () {

            context('when no route name is given', function () {

                it('should proxy the ->addRoute() method with the GET method', function () {

                    $collection = $this->collector->get();

                    $collection->get('/pattern', 'handler');

                    $this->collector->addRoute->calledWith('GET', '/pattern', 'handler');

                });

            });

            context('when a route name is given', function () {

                it('should proxy the ->addRoute() method with the route name and GET method', function () {

                    $collection = $this->collector->get();

                    $collection->get('name', '/pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'GET', '/pattern', 'handler');

                });

            });

        });

        describe('->post()', function () {

            context('when no route name is given', function () {

                it('should proxy the ->addRoute() method with the POST method', function () {

                    $collection = $this->collector->get();

                    $collection->post('/pattern', 'handler');

                    $this->collector->addRoute->calledWith('POST', '/pattern', 'handler');

                });

            });


            context('when a route name is given', function () {

                it('should proxy the ->addRoute() method with the route name and POST method', function () {

                    $collection = $this->collector->get();

                    $collection->post('name', '/pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'POST', '/pattern', 'handler');

                });

            });

        });

        describe('->put()', function () {

            context('when no route name is given', function () {

                it('should proxy the ->addRoute() method with the PUT method', function () {

                    $collection = $this->collector->get();

                    $collection->put('/pattern', 'handler');

                    $this->collector->addRoute->calledWith('PUT', '/pattern', 'handler');

                });

            });

            context('when a route name is given', function () {

                it('should proxy the ->addRoute() method with the route name and PUT method', function () {

                    $collection = $this->collector->get();

                    $collection->put('name', '/pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'PUT', '/pattern', 'handler');

                });

            });

        });

        describe('->delete()', function () {

            context('when no route name is given', function () {

                it('should proxy the ->addRoute() method with the DELETE method', function () {

                    $collection = $this->collector->get();

                    $collection->delete('/pattern', 'handler');

                    $this->collector->addRoute->calledWith('DELETE', '/pattern', 'handler');

                });

            });

            context('when a route name is given', function () {

                it('should proxy the ->addRoute() method with the route name and DELETE method', function () {

                    $collection = $this->collector->get();

                    $collection->delete('name', '/pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'DELETE', '/pattern', 'handler');

                });

            });

        });

        describe('->patch()', function () {

            context('when no route name is given', function () {

                it('should proxy the ->addRoute() method with the PATCH method', function () {

                    $collection = $this->collector->get();

                    $collection->patch('/pattern', 'handler');

                    $this->collector->addRoute->calledWith('PATCH', '/pattern', 'handler');

                });

            });

            context('when a route name is given', function () {

                it('should proxy the ->addRoute() method with the route name and PATCH method', function () {

                    $collection = $this->collector->get();

                    $collection->patch('name', '/pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'PATCH', '/pattern', 'handler');

                });

            });

        });

        describe('->head()', function () {

            context('when no route name is given', function () {

                it('should proxy the ->addRoute() method with the HEAD method', function () {

                    $collection = $this->collector->get();

                    $collection->head('/pattern', 'handler');

                    $this->collector->addRoute->calledWith('HEAD', '/pattern', 'handler');

                });

            });

            context('when a route name is given', function () {

                it('should proxy the ->addRoute() method with the route name and HEAD method', function () {

                    $collection = $this->collector->get();

                    $collection->head('name', '/pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'HEAD', '/pattern', 'handler');

                });

            });

        });

    });

});
