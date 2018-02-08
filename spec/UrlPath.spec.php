<?php

use FastRoute\RouteParser;

use Ellipse\FastRoute\UrlPath;
use Ellipse\FastRoute\Exceptions\PlaceholderTypeException;
use Ellipse\FastRoute\Exceptions\PlaceholderFormatException;

describe('UrlPath', function () {

    beforeEach(function () {

        $this->parser = new RouteParser\Std;

    });

    describe('->value()', function () {

        context('when all the parameters are matching the pattern parts format', function () {

            it('should return an path built with the given parameters', function () {

                $pattern = '/pattern/{p1}/{p2}';

                $parts = current($this->parser->parse($pattern));

                $path = new UrlPath('name', $parts, ['v1', 'v2']);

                $test = $path->value();

                expect($test)->toEqual('/pattern/v1/v2');

            });

            it('should cast the parameters as string', function () {

                $pattern = '/pattern/{p1}/{p2}';

                $parts = current($this->parser->parse($pattern));

                $path = new UrlPath('name', $parts, ['v1', 2]);

                $test = $path->value();

                expect($test)->toEqual('/pattern/v1/2');

            });

        });

        context('when a placeholder is null', function () {

            it('should throw a PlaceholderTypeException', function () {

                $pattern = '/pattern/{p1:[0-9]+}/{p2:[0-9]+}';

                $parts = current($this->parser->parse($pattern));

                $path = new UrlPath('name', $parts, [null, 'v2']);

                $exception = new PlaceholderTypeException(null, 'name', 'p1');

                expect([$path, 'value'])->toThrow($exception);

            });

        });

        context('when a placeholder is an array', function () {

            it('should throw a PlaceholderTypeException', function () {

                $pattern = '/pattern/{p1:[0-9]+}/{p2:[0-9]+}';

                $parts = current($this->parser->parse($pattern));

                $path = new UrlPath('name', $parts, [[], 'v2']);

                $exception = new PlaceholderTypeException([], 'name', 'p1');

                expect([$path, 'value'])->toThrow($exception);

            });

        });

        context('when a placeholder is an object', function () {

            it('should throw a PlaceholderTypeException', function () {

                $instance = new class {};

                $pattern = '/pattern/{p1:[0-9]+}/{p2:[0-9]+}';

                $parts = current($this->parser->parse($pattern));

                $path = new UrlPath('name', $parts, [$instance, 'v2']);

                $exception = new PlaceholderTypeException($instance, 'name', 'p1');

                expect([$path, 'value'])->toThrow($exception);

            });

        });

        context('when a placeholder is a resource', function () {

            it('should throw a PlaceholderTypeException', function () {

                $resource = fopen('php://memory', 'r');

                $pattern = '/pattern/{p1:[0-9]+}/{p2:[0-9]+}';

                $parts = current($this->parser->parse($pattern));

                $path = new UrlPath('name', $parts, [$resource, 'v2']);

                $exception = new PlaceholderTypeException($resource, 'name', 'p1');

                expect([$path, 'value'])->toThrow($exception);

            });

        });

        context('when a parameter does not match a pattern part format', function () {

            it('should throw a PlaceholderFormatException', function () {

                $pattern = '/pattern/{p1:[0-9]+}/{p2:[0-9]+}';

                $parts = current($this->parser->parse($pattern));

                $path = new UrlPath('name', $parts, [1, 'v2']);

                $exception = new PlaceholderFormatException('v2', 'name', 'p2', '[0-9]+');

                expect([$path, 'value'])->toThrow($exception);

            });

        });

    });

});
