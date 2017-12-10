<?php

namespace Paraman;

class ParametersSingleton
{
    public function __construct()
    {
        // create a parameters singleton
        app()->singleton('parameter', function () {
            return Parameter::all();
        });
    }
}
