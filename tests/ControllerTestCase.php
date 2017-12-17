<?php

namespace Paraman\Tests;

abstract class ControllerTestCase extends TestCase
{
    /**
     * @param string $method
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     */
    protected function authUserJson($method, $uri, array $data = [], array $headers = [])
    {
        return $this->actingAs(new User())->json($method, $uri, $data, $headers);
    }
}
