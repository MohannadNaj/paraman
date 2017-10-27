<?php

namespace Parameter;

use Parameter\ParametersManager;

class ParametersValidator {
	public static function newRules($type)
	{
		return static::getRules($type, 'new');
	}

	public static function updateRules($type) {
		return static::getRules($type, 'update');
	}

	private static function getRules($type, $operation)
	{
		$rulesClass = static::getRulesClass($type);
		$rulesMethod = static::getOperationRulesMethod($operation);
		return (new $rulesClass)->$rulesMethod();
	}

	private static function getRulesClass($type) {
		$classPath = static::getRulesClassPath($type);

		if(! class_exists($classPath))
			$classPath = static::getRulesClassPath('_Default');

		return $classPath;
	}

	private static function getOperationRulesMethod($operation) {
			return 'get' . ucfirst($operation) . 'Rules';
	}

	private static function getRulesClassPath($type)
	{
		return ParametersManager::rulesClassPath( $type );
	}
}