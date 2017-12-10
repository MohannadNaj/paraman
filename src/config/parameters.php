<?php

return [
  'default_connection' => 'parameters_default',
  'connections'        => [
    'parameters_default' => [
      'driver'   => 'sqlite',
      'database' => app_path().'/../database/parameters.sqlite',
      'prefix'   => '',
    ],
  ],
  'middleware' => Paraman\Http\Middlewares\ParameterMiddleware::class,
];
