<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\PlaceholderFormatException;

describe('PlaceholderFormatException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new PlaceholderFormatException('value', 'name', 'parameter', 'format');

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

});
