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

}