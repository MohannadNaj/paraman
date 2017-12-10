<?php

namespace Paraman\Tests;

trait ControllerTestTrait
{
    /**
     * @param string $method
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     */
    private function authUserJson($method, $uri, array $data = [], array $headers = [])
    {
        return $this->actingAs(new User())->json($method, $uri, $data, $headers);
    }
}
