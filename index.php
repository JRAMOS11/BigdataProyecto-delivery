<?php
use Utilities\Context;
use Utilities\Site;

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Controllers/AuthExceptions.php';
session_start();

try {
    Site::configure();

    $pageRequest = Site::getPageRequest();

    $prefijosPermitidos = [
        'Controllers\Index',
        'Controllers\Sec\\',
        'Controllers\Admin\\',
        'Controllers\Cocina\\',
        'Controllers\Tracking\\'
    ];

    $rutaPermitida = false;

    foreach ($prefijosPermitidos as $prefijo) {
        if ($pageRequest === $prefijo || str_starts_with($pageRequest, $prefijo)) {
            $rutaPermitida = true;
            break;
        }
    }

    if (!$rutaPermitida || !class_exists($pageRequest)) {
        throw new Exception("Ruta no permitida.");
    }

    $instance = new $pageRequest();
    $instance->run();

    unset($_SESSION['flash_msg']);

} catch (\Controllers\PrivateNoAuthException $ex) {
    $instance = new \Controllers\NoAuth();
    $instance->run();

} catch (\Controllers\PrivateNoLoggedException $ex) {
    $redirTo = urlencode(Context::getContextByKey('request_uri'));
    Site::redirectTo("index.php?page=Sec.Login&redirto=" . $redirTo);

} catch (Exception $ex) {
    Site::logError($ex, 500);
    $instance = new \Controllers\Error();
    $instance->run();

} catch (Error $ex) {
    Site::logError($ex, 500);
    $instance = new \Controllers\Error();
    $instance->run();
}