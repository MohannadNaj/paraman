<?php

return array(
  'default' => 'parameters_default',
  'connections' => array(
    'parameters_default' => array(
      'driver'   => 'sqlite',
      'database' => app_path().'/../database/parameters.sqlite',
      'prefix'   => '',
    ),
  ),
  'middleware' => Parameter\Http\Middlewares\ParameterMiddleware::class,
);