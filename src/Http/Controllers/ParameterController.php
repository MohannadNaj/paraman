<?php

namespace Paraman\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Paraman\Parameter;
use Paraman\ParametersManager;
use Paraman\ParametersValidator;
use Storage;

class ParameterController extends BaseController
{
    public function __construct()
    {
        $this->middleware(config('parameters.middleware'))->except('login');
    }

    public function login()
    {
    }

    public function index()
    {
        $data['parameters'] = collect([]);

        try {
            $data['parameters'] = param();
        } catch (QueryException $e) {
            ParametersManager::handleException($e);

            $needInstallation = ParametersManager::needInstallation();
            $needMigration = ParametersManager::needMigration();

            if (!$needInstallation && !$needMigration) {
                throw $e;
            }
            $data = $data; // + compact('needInstallation','needMigration');
        }

        return view('parameters::index', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, ParametersValidator::newRules($request->type));

        $parameter = Parameter::create($request->only(ParametersManager::$createParameterFields));

        return ['parameter'=>$parameter];
    }

    public function update(Request $request, Parameter $parameter)
    {
        $this->validate($request, ParametersValidator::updateRules($parameter->type));

        $parameter->update($request->all());

        return $parameter;
    }

    public function destroy(Parameter $parameter)
    {
        $parameterCopy = clone $parameter;

        $parameter->delete();

        return ['data'=>$parameterCopy];
    }

    public function addPhoto(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return $this->failedUploadResponse();
        }

        $this->validate($request,
            ['file' => ParametersValidator::updateRules('file')['value']]);

        $path = $request->file->store('uploads', 'local');

        return ['path'=>$path];
    }

    public function updatePhoto(Request $request)
    {
        $this->validate($request, ['path'=>'required', 'parameter'=>'required|integer']);
        $path = $request->path;

        if (!Storage::disk('local')->exists($path)) {
            return $this->failedUploadResponse();
        }

        $local = Storage::disk('local')->get($path);

        $public = Storage::disk('public')->put($path, $local);

        $data = ['path'=> $path, 'url'=> Storage::disk('public')->url($path)];

        $parameter = param()->where('id', $request->parameter)->first();

        $parameter->update(['value'=>$path]);

        $data['parameter'] = $parameter;

        return $data;
    }

    public function choseCategory(Request $request, Parameter $parameter, $category_id = null)
    {
        $parameter->category_id = $category_id;
        $parameter->save();

        return ['parameter'=>$parameter];
    }

    public function addCategory(Request $request)
    {
        $data = ParametersManager::getCategoryDefaults();
        $data['label'] = $request->value;

        $request->merge($data);

        $this->validate($request, ParametersValidator::newRules($request->type));

        $parameter = Parameter::create($request->only(ParametersManager::$addCategoryRequestFields))->fresh();

        return ['parameter'=>$parameter];
    }

    private function failedUploadResponse()
    {
        return response()->json(['Error in uploading file'])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
