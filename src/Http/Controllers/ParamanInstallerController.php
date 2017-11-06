<?php

namespace Parameter\Http\Controllers;

use File;
use Artisan;
use Parameter\ParametersManager;

class ParamanInstallerController extends BaseController
{
    public function __construct()
    {
        $this->middleware(config('parameters.middleware'));
    }

    public function createDB()
    {
        $path = ParametersManager::getDatabasePath();

        $creation = File::put($path, '');

        $status = $creation !== false;

        return compact('path', 'status');
    }

    public function migrate()
    {
        $exitCode = Artisan::call('migrate');
        $output = Artisan::output();

        return compact('exitCode','output');
    }
}
