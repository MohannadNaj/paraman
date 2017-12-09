<?php
namespace Paraman;

use Paraman\Parameter;

class ParametersSingleton {

	public function __construct()
	{
        // create a parameters singleton
        app()->singleton('parameter', function () {
            return Parameter::all();
        });

	}
}