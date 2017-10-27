<?php

namespace Parameter\Tests\Controller;

use Mockery;
use StdClass;
use Parameter\Tests\User;
use Illuminate\Http\UploadedFile;
use Parameter\Tests\ControllerTestCase;
use Illuminate\Support\Facades\Storage;

class ParameterControllerTestCase extends ControllerTestCase
{
    private $uploadedFilePath;
 
    public function test_index()
    {
        $response = $this->actingAs(new User())->get('/parameters');
        $this->seeStatusCode(200);
    }

    public function test_store($data = [])
    {
        $this->json('POST', '/parameters',
            $data +
            ['name' => 'param','category_id' => null, 'type'=>'text', 'label'=>'some_param']
        );
        $responseArray = $this->decodeResponseJson();
        $this->seeStatusCode(200);
        $this->assertArrayHasKey('parameter',$responseArray);
    }

    public function test_update()
    {
        $this->test_store();

        $this->json('PATCH', '/parameters/1',
            ['value'=> 'some-value']
        );
        $responseArray = $this->decodeResponseJson();
        
        $this->assertSame('some-value', param(1), 'parameter value updated to true');

        $this->assertArrayHasKey('meta',$responseArray);
    }

    public function test_delete()
    {
        $this->test_store();

        $this->json('DELETE', '/parameters/1');
        $responseArray = $this->decodeResponseJson();
        
        $this->assertArrayHasKey('data',$responseArray);
    }

    public function test_add_file()
    {
        Storage::fake('local');

        $response = $this->json('POST', '/parameters/addPhoto', [
            'file' => UploadedFile::fake()->image('avatar.jpg')
        ]);
        $response->seeStatusCode(200);

        $responseArray = $response->decodeResponseJson();
        $this->uploadedFilePath = $responseArray['path'];
        Storage::disk('local')->assertExists($responseArray['path']);
    }

    public function test_update_file()
    {
        Storage::fake('public');
        $this->test_store(['type'=>'file']);
        $this->test_add_file();
        $this->json('POST', '/parameters/updatePhoto', [
            'parameter'=> 1, 'path'=> $this->uploadedFilePath
        ]);
        $this->seeStatusCode(200);
        Storage::disk('public')->assertExists($this->uploadedFilePath);
        $this->assertSame($this->uploadedFilePath, param(1));
    }
    public function test_validation_on_create_parameter()
    {
        $response = $this->actingAs(new User())->json('POST', '/parameters', ['name' => 'param','category_id' => 'e']);
        $response->seeStatusCode(422);
        $responseArray = $response->decodeResponseJson();
        $this->assertArrayContains(['category_id','label','type'], array_keys($responseArray['errors']));
    }

    public function test_upload_validate_file()
    {

        $response = $this->json('POST', '/parameters/addPhoto', [
         'file'=>'not a file!'
        ]);
        $response->seeStatusCode(422);
    }

    public function test_category_parameter()
    {
        // create parameter
        $this->json('POST', '/parameters',
            ['name' => 'param','category_id' => null, 'type'=>'text', 'label'=>'some_param']
        );

        // add category
        $this->json('POST', '/parameters/addCategory',
            ['value'=>'category-name']
        );

        // assign parameter to category
        $response = $this->json('POST', '/parameters/1/category/2');
        $response->seeStatusCode(200);

        $this->assertEquals(param()[0]->category->value, 'category-name');
    }
}