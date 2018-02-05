<?php

use function Eloquent\Phony\Kahlan\mock;

use Ellipse\FastRoute\Url;
use Ellipse\FastRoute\UrlPath;

describe('Url', function () {

    beforeEach(function () {

        $this->path = mock(UrlPath::class);

    });

    describe('->value()', function () {

        beforeEach(function () {

            $this->path->value->returns('url');

        });

        context('when no array of query parameters is given', function () {

            context('when no fragment is given', function () {

                it('should proxy the delegate', function () {

                    $url = new Url($this->path->get());

                    $test = $url->value();

                    expect($test)->toEqual('url');

                });

            });

            context('when a fragment is given', function () {

                it('should append the fragment to the delegate url', function () {

                    $url = new Url($this->path->get(), [], 'fragment');

                    $test = $url->value();

                    expect($test)->toEqual('url#fragment');

                });

            });

        });

        context('when an array of query parameters is given', function () {

            context('when no fragment is given', function () {

                it('should append the query parameters to the delegate url', function () {

                    $url = new Url($this->path->get(), ['q1' => 'v1', 'q2' => 'v2']);

                    $test = $url->value();

                    expect($test)->toEqual('url?q1=v1&q2=v2');

                });

            });

            context('when a fragment is given', function () {

                it('should append the fragment after the query parameters to the delegate url', function () {

                    $url = new Url($this->path->get(), ['q1' => 'v1', 'q2' => 'v2'], 'fragment');

                    $test = $url->value();

                    expect($test)->toEqual('url?q1=v1&q2=v2#fragment');

                });

            });

        });

    });

});
