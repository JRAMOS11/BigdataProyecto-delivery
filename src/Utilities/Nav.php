<?php
 
namespace Utilities;
 
class Nav
{
    private function __construct() {}
    private function __clone() {}
 
    public static function setPublicNavContext(): void
    {
        if (Context::getContextByKey('PUBLIC_NAVIGATION') !== '') {
            return;
        }
        $data = self::loadJson()['public'] ?? [];
        Context::setContext('PUBLIC_NAVIGATION', $data);
    }
 
    public static function setNavContext(): void
    {
        if (Context::getContextByKey('NAVIGATION') !== '') {
            return;
        }
 
        $rol  = Security::getUserRole();
        $data = self::loadJson()['private'] ?? [];
 
        $data = array_values(array_filter($data, function($item) use ($rol) {
            if (!isset($item['roles'])) return true;
            return in_array($rol, $item['roles'], true);
        }));
 
        Context::setContext('NAVIGATION', $data);
    }
 
    public static function invalidate(): void
    {
        Context::removeContextByKey('NAVIGATION');
        Context::removeContextByKey('PUBLIC_NAVIGATION');
        Context::removeContextByKey('NAV_JSON');
    }
 
    private static function loadJson(): array
    {
        $cached = Context::getContextByKey('NAV_JSON');
        if ($cached !== '') {
            return json_decode($cached, true);
        }
        $path = __DIR__ . '/../../nav.config.json';
        if (!file_exists($path)) {
            throw new \RuntimeException("nav.config.json not found");
        }
        $json = file_get_contents($path);
        Context::setContext('NAV_JSON', $json);
        return json_decode($json, true);
    }
}
 