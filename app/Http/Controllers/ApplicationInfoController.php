<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ApplicationInfoController
{
    /**
     * Returns information about PHP's configuration
     */
    function getPhpInfo()
    {
        phpinfo();
    }

    /**
     * Returns client's info from HTTP request
     */
    function getClientInfo() : JsonResponse
    {
        return new JsonResponse([
            'client_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Returns information about database's configuration
     */
    function getDatabaseInfo() : JsonResponse
    {
        $driver = config('database.default');
        return new JsonResponse([
            'driver' => $driver,
            'host' => config("database.connections.$driver.host"),
            'port' => config("database.connections.$driver.port"),
            'database' => config("database.connections.$driver.database"),
            'username' => config("database.connections.$driver.username"),
            'password' => config("database.connections.$driver.password"),
            'charset' => config("database.connections.$driver.charset"),
            'collation' => config("database.connections.$driver.collation"),
            'prefix' => config("database.connections.$driver.prefix"),
            'schema' => config("database.connections.$driver.schema"),
            'engine' => config("database.connections.$driver.engine"),
            'options' => config("database.connections.$driver.options"),
        ]);
    }
}
