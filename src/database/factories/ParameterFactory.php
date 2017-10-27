<?php

use Parameter\Parameter;
use Parameter\ParametersManager;
use Illuminate\Http\UploadedFile;

$factory->define(
	Parameter::class,
	function (Faker\Generator $faker, $data = []) {
		$mergeData = [
//			'name' => '',
//			'label' => '',
//			'type' => '',
			'is_category' => false,
			'category_id' => null,
//			'value' => '',
			'meta' => [],
			'created_at' =>  date('Y-m-d H:i:s'),
			'updated_at' =>  date('Y-m-d H:i:s'),
	    ];

 		if(empty($data['type']))
	 		$data['type'] = collect(ParametersManager::getSupportedTypes())->random();

 		if(empty($data['name']))
	 		$data['name'] = $faker->slug(3);

 		if(empty($data['label']))
	 		$data['label'] = $faker->sentence;

 		if(! empty($data['value']) && empty($data['type']) )
		    return $data + $mergeData;

 		switch ($data['type']) {
 			case 'textfield':
	 			$data['value'] = $faker->sentence(2);
	 			break;
 			case 'text':
	 			$data['value'] = $faker->realText;
	 			break;
 			case 'file':
	 			$data['value'] = UploadedFile::fake()->image( $faker->slug(2). '.jpg');
	 			break;
 			case 'integer':
	 			$data['value'] = $faker->randomNumber;
	 			break;
 			case 'boolean':
	 			$data['value'] = $faker->boolean ;
	 			break;
 		}

	    return $data + $mergeData;
	});