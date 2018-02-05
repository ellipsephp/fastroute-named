<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\RouteNameTypeException;

describe('RouteNameTypeException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new RouteNameTypeException(1);

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

});
