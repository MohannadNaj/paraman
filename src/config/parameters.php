<?php

return array(
  'default_connection' => 'parameters_default',
  'connections' => array(
    'parameters_default' => array(
      'driver'   => 'sqlite',
      'database' => app_path().'/../database/parameters.sqlite',
      'prefix'   => '',
    ),
  ),
  'middleware' => Paraman\Http\Middlewares\ParameterMiddleware::class,
);