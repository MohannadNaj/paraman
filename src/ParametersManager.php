<?php

namespace Paraman;

use Closure;
use Exception;
use File;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class ParametersManager
{
    /**
     * The callback that should be used to authenticate Paraman users.
     *
     * @var \Closure
     */
    public static $authUsing;

    public static $needMigration = false;

    public static $supportedTypes = ['textfield', 'text', 'file', 'integer', 'boolean'];

    private static $typesInterface = 'Paraman\\Types\\%s\\';

    private static $extensionTypes = [];

    public static $addCategoryRequestFields = ['is_category', 'value', 'name', 'type', 'label'];

    public static $createParameterFields = ['name', 'type', 'label', 'category_id'];

    /**
     * Determine if the given request can access the Paraman dashboard.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: function () use ($request) {
            if (empty($request->user())
            || !method_exists($request->user(), 'canEditParameters')) {
                return app()->environment('local');
            }

            return $request->user()->canEditParameters();
        })($request);
    }

    /**
     * Set the callback that should be used to authenticate Paraman users.
     *
     * @param \Closure $callback
     *
     * @return static
     */
    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;

        return new static();
    }

    public static function getSupportedTypes()
    {
        return array_merge(array_keys(static::$extensionTypes), static::$supportedTypes);
    }

    public static function getDatabasePath()
    {
        return config('database.connections.parameters.database');
    }

    public static function handleException(Exception $e)
    {
        if ($e instanceof QueryException) {
            if (static::needMigrationMessage($e->getMessage())) {
                static::$needMigration = true;
            }
        }
        //->getMessage()
    }

    public static function needMigrationMessage($message)
    {
        return str_contains($message, 'no such table') || str_contains($message, 'does not exist');
    }

    public static function extend($type, $class)
    {
        if (!Str::endsWith($class, '\\')) {
            $class .= '\\';
        }

        static::$extensionTypes[lcfirst($type)] = $class;
    }

    public static function unextend($type)
    {
        unset(static::$extensionTypes[lcfirst($type)]);
    }

    public static function needMigration()
    {
        return static::$needMigration;
    }

    public static function needInstallation()
    {
        return config('database.connections.parameters')
                === config('parameters.connections.parameters_default')
                && config('database.connections.parameters.driver') == 'sqlite'
                && !file_exists(static::getDatabasePath());
    }

    public static function getCategoryDefaults()
    {
        return ['type' => 'textfield',
        'name'         => 'category-'.Str::random(),
        'is_category'  => true,
        ];
    }

    public static function clientData()
    {
        $parametersColumns = static::getParametersColumns();
        $needInstallation = static::needInstallation();
        $needMigration = static::needMigration();

        $clientData = compact('needInstallation', 'needMigration') + [
            'csrfToken'         => csrf_token(),
            'appName'           => config('app.name'),
            'images_dir'        => 'storage',
            'base_url'          => url('/').'/',
            'parametersColumns' => array_fill_keys($parametersColumns, null),
            'parametersTypes'   => static::getSupportedTypes(),
        ];

        if ($needInstallation || $needMigration) {
            $clientData['installationData'] = static::getInstallationData();
        }

        return $clientData;
    }

    public static function getInstallationData()
    {
        return ['databasePath' => static::getDatabasePath(),
            'migrationPaths'   => app('migrator')->getMigrationFiles(app('migrator')->paths()),
            'command'          => 'artisan migrate', ];
    }

    public static function getParametersColumns()
    {
        try {
            return Parameter::getColumns();
        } catch (QueryException $e) {
            return (array) json_decode(File::get(__DIR__.'/database/parameters-columns.json'));
        }
    }

    private static function getTypesInterface($type = null)
    {
        if (is_null($type)) {
            return static::$typesInterface;
        }

        if (in_array($type, array_keys(static::$extensionTypes))) {
            return static::$extensionTypes[$type];
        }

        return sprintf(static::$typesInterface, ucfirst($type));
    }

    public static function __callStatic($method, $args)
    {
        if (!Str::contains($method, 'ClassPath')) {
            return;
        }

        $target = ucfirst(explode('ClassPath', $method)[0]);

        return static::getTypesInterface(...$args).$target;
    }
}
