<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\PlaceholderTypeException;

describe('PlaceholderTypeException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new PlaceholderTypeException(1, 'name', 'placeholder');

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

});
