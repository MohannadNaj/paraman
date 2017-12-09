<?php

use Parameter\Parameter;

// return parameters collection, or a specified parameter value
if (! function_exists('param')) {
    function param($name = null, $type = null, $value = null)
    {
        // if we are using the method to return a specified parameter value, e.g: param('some_param')
        if ($name) {
        	$param = app('parameter')->where('name', $name)->first();
        	if(! $param) {
        		$param = app('parameter')->where('id', $name)->first();
        	}

            if(! $param) {
                $param = Parameter::create(compact('name','type','value'));
            }

    		return $param ? $param->getValue() : null;
        }

        // if the method is used to return the parameters collection
        return app('parameter');
    }
}
