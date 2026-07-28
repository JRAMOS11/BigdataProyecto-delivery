<?php

namespace Utilities;

class Site
{
    private function __construct() {}

    public static function configure(): void
    {
        $env = new DotEnv(__DIR__ . '/../../parameters.env');
        Context::setArrayToContext($env->load());
        date_default_timezone_set(Context::getContextByKey('TIMEZONE'));
        Context::setContext('CURRENT_YEAR', date('Y'));
    }

    public static function getPageRequest(): string
    {
        $default = Security::isLogged()
            ? Context::getContextByKey('PRIVATE_DEFAULT_CONTROLLER')
            : Context::getContextByKey('PUBLIC_DEFAULT_CONTROLLER');

        $pageRequest = $default;

        if (isset($_GET['page'])) {
            $pageRequest = str_replace(['.', '_', '-'], '\\', $_GET['page']);
        }

        Context::setArrayToContext($_GET);
        Context::setContext('request_uri', $_SERVER['REQUEST_URI']);

        return 'Controllers\\' . $pageRequest;
    }

    public static function redirectTo(string $url, string $msg = ''): void
    {
        if ($msg !== '') {
            $_SESSION['flash_msg'] = $msg;
        }
        header('Location: ' . $url);
        exit;
    }

    public static function logError(\Throwable $ex, int $code = 500): void
    {
        error_log((string) $ex);
        Context::setContext('ERROR_CODE', $code);
        Context::setContext('ERROR_MSG', $ex->getMessage());
    }

    public static function addLink(string $href): void
    {
        $links   = Context::getContextByKey('SiteLinks') ?: [];
        $links[] = $href;
        Context::setContext('SiteLinks', $links);
    }

    public static function addEndScript(string $src): void
    {
        $scripts   = Context::getContextByKey('EndScripts') ?: [];
        $scripts[] = $src;
        Context::setContext('EndScripts', $scripts);
    }
}