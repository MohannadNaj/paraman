<?php

namespace Parameter\Tests\Controller;

use Mockery;
use Closure;
use StdClass;
use Parameter\Tests\User;
use Parameter\ParametersManager;
use Illuminate\Http\UploadedFile;
use Parameter\Tests\DatabaseTestTrait;
use Parameter\Tests\ControllerTestCase;
use Illuminate\Support\Facades\Storage;

class ParamanInstallerControllerTest extends ControllerTestCase
{
    private $dbPath;

    public function setUp()
    {
        parent::setUp();
        $this->dbPath = ParametersManager::getDatabasePath();
        $this->removeDBFile();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->removeDBFile();
    }

    private function removeDBFile()
    {
        // remove the created database file -if exists-.
        if(file_exists($this->dbPath)) {
            @unlink($this->dbPath);
        }
    }

    public function test_install_createDB()
    {
        //assert we are running this test in need-installation environment
        $this->assertTrue(ParametersManager::needInstallation());
        $this->authUserJson('POST', '/parameters/createDB');

        $this->seeStatusCode(200);

        $response = $this->decodeResponseJson();

        $this->assertTrue(!empty($response['path']));
        $this->assertTrue(file_exists($response['path']));
    }

    public function test_install_migrate()
    {
        $this->test_install_createDB();
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        $this->authUserJson('POST', '/parameters/migrate');

        $this->seeStatusCode(200);

        $this->assertEquals(param()->count(), 0);

        $response = $this->decodeResponseJson();

        $this->assertTrue(isset($response['exitCode']));
    }
}
