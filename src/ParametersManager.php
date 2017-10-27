<?php

namespace Parameter;

use Illuminate\Support\Str;

class ParametersManager {
    public static $supportedTypes = ['textfield','text','file','integer','boolean'];

    private static $typesInterface = 'Parameter\\Types\\%s\\';

    public static $addCategoryRequestFields = ['is_category', 'value', 'name','type','label'];

    public static $createParameterFields = ['name','type','label','category_id'];

    public static function getSupportedTypes() {
        return static::$supportedTypes;
    }

    public static function getCategoryDefaults() {

        return ['type' => 'textfield',
        'name' => 'category-' . Str::random(),
        'is_category' => true,
        ];
    }

    public static function clientData() {
    	$parametersColumns = Parameter::getColumns();

    	return [
            'csrfToken' => csrf_token(),
            'images_dir' => 'storage',
            'base_url' => url('/') . '/',
            'parametersColumns' =>  array_fill_keys($parametersColumns, null ),
            'parametersTypes'=> static::getSupportedTypes(),
        ];
    }
    private static function getTypesInterface($type = null)
    {
    	if(is_null($type))
	    	return static::$typesInterface;

	    return sprintf(static::$typesInterface, ucfirst($type));
    }

    public static function __callStatic($method, $args) {
    	if(! Str::contains($method, 'ClassPath'))
    		return null;

		$target = ucfirst(explode('ClassPath',$method)[0]);

		return static::getTypesInterface(...$args) . $target;
    }
}