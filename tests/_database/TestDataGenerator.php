<?php

namespace Parameter\Tests\_database;

use File;
use Mockery;
use StdClass;
use Parameter\Parameter;
use Faker\Factory as Faker;
use Parameter\ParameterObserver;
use Parameter\ParametersManager;
use Parameter\Tests\ModelTestCase;

class TestDataGenerator extends ModelTestCase
{
    protected $output = "../../specs/setup/testData.json"; // relative to __DIR__
    protected $faker;

    public function setup()
    {
        parent::setup();
        $this->faker = Faker::create();
    }

    public function test_generate_test_data_for_client()
    {
        $testData = [
            'clientData' => ParametersManager::clientData(),
            'parameters' => factory(Parameter::class, 20)
                ->create()
                ->toArray(),
            // types .. without file?
            'integer' => $this->createType('integer', 3)
                ->toArray(),
            'boolean' => $this->createType('boolean', 3)
                ->toArray(),
            'textfield' => $this->createType('textfield', 3)
                ->toArray(),
            'text' => $this->createType('text', 3)
                ->toArray(),
        ];

        $testData['categories'] = factory(Parameter::class, 3)
            ->create(['type'=>'textfield','is_category'=>true])
            ->toArray();

        $testData['categorized_parameters'] = $this->getCategorizedParameters(
            $testData['categories']
        );

        $testData['modified_parameters'] = $this->getModifiedParameters();

        $outputPath = __DIR__ . '/'. $this->output;

        File::put($outputPath, json_encode($testData, JSON_PRETTY_PRINT));

        $this->assertTrue(File::exists($outputPath), 'test data is generated');
    }

    protected function getModifiedParameters($count = 5)
    {
        $data = $this->createType('boolean', $count)
        ->merge($this->createType('integer', $count))
        ->merge($this->createType('textfield', $count))
        ->merge($this->createType('text', $count));

        // modify the parameter at least once
        foreach ($data as $parameter) {
            for ($i=0; $i <$this->faker->numberBetween(1,3); $i++) { 
                $parameter->value = 
                    $this->{
                        'modify' . ucfirst($parameter->type)
                    }($parameter);

                $parameter->save();
            }
        }

        return $data;
    }

    protected function getCategorizedParameters($categories)
    {
        $categorizedParameters = [];

        foreach ($categories as $category) {
            $categorizedParameters =
                array_merge(
                    $categorizedParameters ,
                    factory(Parameter::class, 5)
                    ->create(['category_id' => $category['id']])
                    ->toArray()
                );
        }

        return $categorizedParameters;
    }

    protected function createType($type = null, $count = 1)
    {
        return factory(Parameter::class, $count)
            ->create(is_null($type) ? null : ['type'=>$type]);
    }
    protected function modifyBoolean($parameter = null) {
        return ! $parameter->value;
    }

    protected function modifyInteger($parameter = null) {
        return ( $parameter->value / 2) +
            $this->faker->numberBetween(1, $parameter->value - 1);
    }

    protected function modifyTextfield($parameter = null) {
        return $this->faker->word . ' '
                . $this->makeFakeValueFor('textfield');
    }

    protected function modifyText($parameter = null) {
        return $this->faker->word . ' '
                . $this->makeFakeValueFor('text');
    }

    protected function makeFakeValueFor($type) {
        return factory(Parameter::class)
            ->make(['type'=>$type])
            ->value;
    }
}
