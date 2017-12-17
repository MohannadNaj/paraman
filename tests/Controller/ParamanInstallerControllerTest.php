<?php

namespace Paraman\Tests\Controller;

use Mockery;
use Closure;
use StdClass;
use Paraman\Tests\User;
use Paraman\ParametersManager;
use Illuminate\Http\UploadedFile;
use Paraman\Tests\DatabaseTestTrait;
use Paraman\Tests\ControllerTestCase;
use Illuminate\Support\Facades\Storage;

class ParamanInstallerControllerTest extends ControllerTestCase
{
    private $dbPath;

    public function setUp()
    {
        parent::setUp();
        $this->dbPath = ParametersManager::getDatabasePath();
    }

    public function test_install_createDB($thenRemoveDB = true)
    {
        //assert we are running this test in need-installation environment
        if(file_exists($this->dbPath))
            @unlink($this->dbPath);
        $this->assertTrue(ParametersManager::needInstallation());
        $this->authUserJson('POST', '/parameters/createDB');

        $this->seeStatusCode(200);

        $response = $this->decodeResponseJson();

        $this->assertTrue(!empty($response['path']));
        $this->assertTrue(file_exists($response['path']));
        if($thenRemoveDB)
            @unlink($this->dbPath);
    }

    public function test_install_migrate()
    {
        $this->test_install_createDB(false);
        config()->set('database.default', 'testing');
        config()->set('database.connections.parameters', config('database.connections.testing'));
        $this->authUserJson('POST', '/parameters/migrate');

        $this->seeStatusCode(200);

        $this->assertEquals(param()->count(), 0);

        $response = $this->decodeResponseJson();
        $responseString = strtolower(implode('', (array) $response['output']));

        foreach (['migrated','parameters'] as $message) {
            $this->assertContains($message, $responseString);
        }

        $this->assertTrue(isset($response['exitCode']));
        @unlink($this->dbPath);
    }
}
