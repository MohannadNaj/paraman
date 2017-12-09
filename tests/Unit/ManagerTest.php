<?php

namespace Paraman\Tests\Unit;

use File;
use Mockery;
use StdClass;
use Paraman\Tests\User;
use Paraman\ParametersManager;
use Paraman\Tests\UnitTestCase;

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
		$this->assertEquals('Paraman\Types\Text\Builder' ,
			ParametersManager::builderClassPath('text'));
	}

	public function test_can_extend_types()
	{
		$oldTypes = ParametersManager::getSupportedTypes();

		ParametersManager::extend('custom', 'App\Custom');
		
		$oldTypes[] = 'custom';

		$this->assertArrayContains(['custom'], ParametersManager::getSupportedTypes());
		$this->assertArrayContains($oldTypes, ParametersManager::getSupportedTypes());

		$this->assertEquals('App\Custom\Builder' ,
			ParametersManager::builderClassPath('custom'));

		ParametersManager::unextend('custom');
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

	public function test_auth_visitor()
	{
		$this->assertFalse(ParametersManager::check(request()));
	}

	public function test_auth_canEditParameters_user()
	{
		request()->setUserResolver(function () {
		    return new User();
		});

		$this->assertTrue(ParametersManager::check(request()));
	}

	public function test_auth_local_environment()
	{
		app()->detectEnvironment(function() { return 'local';});
		$this->assertTrue(ParametersManager::check(request()));
	}

	public function test_auth_callback()
	{
		ParametersManager::auth(function() {
			return false;
		});
		$this->assertFalse(ParametersManager::check(request()));
		ParametersManager::auth(function() {
			return true;
		});
		$this->assertTrue(ParametersManager::check(request()));
	}

	public function test_client_data_has_installation_data()
	{
		$this->assertTrue(ParametersManager::needInstallation());

		$clientData = ParametersManager::clientData();

		$this->assertArrayContains(['installationData'], array_keys($clientData));
		$this->assertArrayContains(['databasePath','migrationPaths'], array_keys($clientData['installationData']));
	}

	public function test_client_data_has_no_installation_data_if_dont_need_installation()
	{
		$this->test_need_installation_database_driver_changed();

		$this->assertFalse(ParametersManager::needInstallation());

		$clientData = ParametersManager::clientData();

		$this->assertFalse(isset($clientData['installationData']));
	}

}