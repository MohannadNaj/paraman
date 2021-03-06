<?php

namespace Paraman\Tests\Model;

use Mockery;
use Paraman\Parameter;
use Paraman\ParameterObserver;
use Paraman\ParametersManager;
use Paraman\Tests\ModelTestCase;

class ParameterModelTest extends ModelTestCase
{
    public function test_parameter_contains_columns_for_frontend()
    {
        $columns = Parameter::getColumns();
        $essentialColumns = [
            'id', 'name', 'label', 'type', 'meta',
            'is_category', 'category_id', 'value', ];
        $this->assertArrayContains($essentialColumns, $columns);
    }

    public function test_values_casts_properly()
    {
        factory(Parameter::class)->create(['type'=>'boolean', 'value'=>true]);

        factory(Parameter::class)->create(['type'=>'integer', 'value'=>2]);

        factory(Parameter::class)->create(['type'=>'textfield', 'value'=>3]);

        factory(Parameter::class)->create(['type'=>'text', 'value'=>4]);

        $this->assertSame(true, param(1));

        $this->assertSame(2, param(2));

        $this->assertSame('3', param(3));

        $this->assertSame('4', param(4));
    }

    public function test_parameter_observer_is_called()
    {
        $observer = Mockery::mock(ParameterObserver::class);
        $observer
        ->shouldReceive('saving')
        ->times(2)
        ->shouldReceive('saved')
        ->times(2)
        ->shouldReceive('updating')
        ->once()
        ->shouldReceive('updated')
        ->once()
        ->shouldReceive('deleting')
        ->once()
        ->shouldReceive('deleted')
        ->once();

        app()->bind(ParameterObserver::class, function () use ($observer) {
            return $observer;
        });

        $parameter = factory(Parameter::class)->create(['type'=>'boolean', 'value'=>true]);

        $parameter->value = false;
        $parameter->save();

        $parameter->delete();
    }

    public function test_parameters_signleton_is_up2date()
    {
        $this->assertEquals(0, param()->count());

        // on add
        $types = collect(ParametersManager::getSupportedTypes());

        for ($i = 0; $i < 5; $i++) {
            factory(Parameter::class)->create(['type'=>$types->random()]);
        }

        $this->assertEquals(5, param()->count());

        // on delete
        param()->random()->delete();
        param()->random()->delete();

        $this->assertEquals(3, param()->count());

        // on add & update
        $parameter = factory(Parameter::class)->create(['type'=>'integer']);

        $this->assertEquals(4, param()->count());
        $this->assertEquals($parameter->value + 10, param()->last()->value + 10);

        $parameter->value = 9;
        $parameter->save();

        $this->assertEquals(9, param()->last()->value);
    }

    public function test_extend_types()
    {
        ParametersManager::extend('customType', 'Paraman\Tests\Model\CustomType');

        Parameter::create(['type'=> 'customType', 'value'=> 'foo bar', 'name'=>'custom_foo_bar']);

        $this->assertEquals(param('custom_foo_bar'), 'fo');
        $this->assertArrayContains(['customType'], ParametersManager::clientData()['parametersTypes']);
        ParametersManager::unextend('customType');
    }

    public function test_changes_are_logged()
    {
        $parameter = factory(Parameter::class)->create(['type'=>'boolean', 'value'=> false]);

        $this->assertSame($parameter->meta, []);

        $parameter->value = true;
        $parameter->save();

        $this->assertSame(false, array_get($parameter->meta, 'logs.0.old'));
        $this->assertSame('value', array_get($parameter->meta, 'logs.0.field'));
        $this->assertSame(true, array_get($parameter->meta, 'logs.0.new'));

        $parameter->value = false;
        $parameter->save();

        $this->assertSame(true, array_get($parameter->meta, 'logs.1.old'));
        $this->assertSame('value', array_get($parameter->meta, 'logs.1.field'));
        $this->assertSame(false, array_get($parameter->meta, 'logs.1.new'));
    }

    public function test_helper_method_can_create()
    {
        $param = param('not_exist_parameter_name', 'boolean');

        // assert creation
        $this->assertEquals(param()->count(), 1);
        // assert returning value & proper casting
        $this->assertSame($param, false);
    }

    public function test_helper_method_create_initial_value()
    {
        $param = param('foo_bar_param', 'textfield', 'foo bar');

        $this->assertSame(param('foo_bar_param'), 'foo bar');
    }

    public function test_helper_method_label_equals_name()
    {
        $param = param('foo', 'textfield');

        $this->assertSame('foo',
            param()->where('name', 'foo')->first()->label
        );
    }
}
