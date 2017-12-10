<?php

namespace Paraman\Tests;

use Mockery;
use Orchestra\Testbench\BrowserKit\TestCase as OrchestraTestCase;
use Paraman\Providers\ParametersServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    use TestHelper;

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ParametersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
    }
}
