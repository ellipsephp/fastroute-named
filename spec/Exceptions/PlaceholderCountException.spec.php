<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\PlaceholderCountException;

describe('PlaceholderCountException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new PlaceholderCountException('name', [1, 2], 0);

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

    describe('->getMessage()', function () {

        context('when exactly 0 placeholder is required', function () {

            it('should contain \'don\'t require any placeholder\'', function () {

                $exception = new PlaceholderCountException('name', [0], 1);

                $test = $exception->getMessage();

                expect($test)->toContain('don\'t require any placeholder');

            });

        });

        context('when exactly 1 placeholder is required', function () {

            it('should contain \'exactly 1 placeholder\'', function () {

                $exception = new PlaceholderCountException('name', [1], 1);

                $test = $exception->getMessage();

                expect($test)->toContain('exactly 1 placeholder');

            });

        });

        context('when exactly n placeholder is required', function () {

            it('should contain \'exactly n placeholders\'', function () {

                $exception = new PlaceholderCountException('name', [2], 1);

                $test = $exception->getMessage();

                expect($test)->toContain('exactly 2 placeholders');

            });

        });

        context('when there is multiple possible number of placeholders', function () {

            it('should contain \'between n and m placeholders\'', function () {

                $exception = new PlaceholderCountException('name', [1, 2], 1);

                $test = $exception->getMessage();

                expect($test)->toContain('between 1 and 2 placeholders');

            });

        });

    });

});
