<?php

Route::middleware('web')
     ->namespace('Parameter\Http\Controllers')
     ->group(function() {

    Route::get('parameters','ParameterController@index')->name('parameters.index');
});

Route::middleware('api')
     ->namespace('Parameter\Http\Controllers')
     ->group(function() {

    Route::post('parameters','ParameterController@store')->name('parameters.store');
    Route::patch('parameters/{parameter}','ParameterController@update')->name('parameters.update');
    Route::delete('parameters/{parameter}','ParameterController@destroy')->name('parameters.destroy');

    Route::post('parameters/addPhoto','ParameterController@addPhoto')->name('parameters.addPhoto');
    Route::post('parameters/updatePhoto','ParameterController@updatePhoto')->name('parameters.updatePhoto');
    
    Route::post('parameters/{parameter}/category/{category_id?}','ParameterController@choseCategory')->name('parameters.choseCategory');
    Route::post('parameters/addCategory','ParameterController@addCategory')->name('parameters.addCategory');
});

