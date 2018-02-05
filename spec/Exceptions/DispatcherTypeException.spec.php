<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\DispatcherTypeException;

describe('DispatcherTypeException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new DispatcherTypeException('name');

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

});
