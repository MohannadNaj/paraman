<?php

namespace Parameter\Tests\Unit;

use Mockery;
use StdClass;
use Parameter\Tests\UnitTestCase;
use Parameter\ParametersManager;

class ManagerTest extends UnitTestCase
{
	public function test_static_arrays_visible()
	{
		$this->assertArrayContains(['textfield','boolean'], ParametersManager::getSupportedTypes() );
		$this->assertArrayContains(['name'], ParametersManager::$addCategoryRequestFields);
		$this->assertArrayContains(['name'], ParametersManager::$createParameterFields);
	}

	public function test_static_call_class_path()
	{
		$this->assertEquals('Parameter\Types\Text\Builder' ,
			ParametersManager::builderClassPath('text'));
	}

	public function test_get_category_defaults()
	{
		$categoryDefaults = ParametersManager::getCategoryDefaults();

		$this->assertArrayContains(
			['is_category','name','type'],
			array_keys($categoryDefaults)
		);

		$this->assertTrue($categoryDefaults['is_category']);
	}

	public function test_catch_exception_on_get_parameters_columns()
	{
		$parametersColumns = ParametersManager::getParametersColumns();

		// on this test no database is configured
		$this->assertArrayContains(
			['id','is_category','name','type'],
			array_keys($parametersColumns)
		, 'Try/Catch retrieving parameter columns if the database not configured properly');

		$this->assertNull($parametersColumns['name']);
	}

	public function test_need_installation_no_database()
	{
		// note: on this whole unit test case no database is configured
		$this->assertTrue(ParametersManager::needInstallation(), 
			'return true if no database is configured and the default `sqlite` configuration still in use');
	}
	public function test_need_installation_database_driver_changed()
	{
		// database driver changed
		config()->set('database.connections.parameters.driver','mysql');

		$this->assertFalse(ParametersManager::needInstallation(), 
			'return false if the default configuration the database driver is changed');
	}

	public function test_need_installation_database_config_changed()
	{
		$this->assertTrue(ParametersManager::needInstallation());

		config()->set('database.connections.parameters.prefix','aaa');

		$this->assertFalse(ParametersManager::needInstallation(), 
			'return false if any key or value in the default configuration is modified');
	}

	public function test_need_installation_database_file()
	{
		file_put_contents(ParametersManager::getDatabasePath(), "");

		$this->assertFalse(ParametersManager::needInstallation(), 
			'return false if the database is created');

		unlink(ParametersManager::getDatabasePath());

		$this->assertTrue(ParametersManager::needInstallation());
	}

}