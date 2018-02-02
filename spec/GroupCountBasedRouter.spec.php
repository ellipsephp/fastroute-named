<?php

use function Eloquent\Phony\Kahlan\stub;

use Ellipse\FastRoute\Router;
use Ellipse\FastRoute\GroupCountBasedRouter;

describe('GroupCountBasedRouter', function () {

    beforeEach(function () {

        $this->router = new GroupCountBasedRouter(stub());

    });

    it('should extend Router', function () {

        expect($this->router)->toBeAnInstanceOf(Router::class);

    });

});
