<?php
namespace Parameter;

use Parameter\Parameter;

class ParametersSingleton {

	public function __construct()
	{
        // create a parameters singleton
        app()->singleton('parameter', function () {
            return Parameter::all();
        });

	}
}