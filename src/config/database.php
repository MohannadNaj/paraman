<?php

return array(
  'default' => 'default',
  'connections' => array(
    'default' => array(
      'driver'   => 'sqlite',
      'database' => app_path().'/../database/parameters.sqlite',
      'prefix'   => '',
    ),
  )
);