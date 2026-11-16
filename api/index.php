<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$runtimeStorage = '/tmp/storage';

foreach ([
    $runtimeStorage.'/app/public',
    $runtimeStorage.'/framework/cache/data',
    $runtimeStorage.'/framework/sessions',
    $runtimeStorage.'/framework/views',
    $runtimeStorage.'/logs',
] as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0775, true);
    }
}

$runtimeDefaults = [
    'CACHE_DRIVER' => 'array',
    'LOG_CHANNEL' => 'stderr',
    'SESSION_DRIVER' => 'cookie',
    'VIEW_COMPILED_PATH' => $runtimeStorage.'/framework/views',
];

foreach ($runtimeDefaults as $key => $value) {
    if (getenv($key) === false && ! isset($_ENV[$key]) && ! isset($_SERVER[$key])) {
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->useStoragePath($runtimeStorage);

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
