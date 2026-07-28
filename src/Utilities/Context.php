<?php

namespace Utilities;

class Context
{
    private static array $_context = [];

    private function __construct() {}

    public static function getContext(): array
    {
        return self::$_context;
    }

    public static function setContext(string $key, $value, bool $saveToSession = false): void
    {
        self::$_context[$key] = $value;
        if ($saveToSession) {
            $_SESSION[$key] = $value;
        }
    }

    public static function getContextByKey(string $key)
    {
        if (isset(self::$_context[$key])) {
            return self::$_context[$key];
        }
        return $_SESSION[$key] ?? '';
    }

    public static function setArrayToContext(array $values, bool $saveToSession = false): void
    {
        foreach ($values as $key => $value) {
            self::$_context[$key] = $value;
            if ($saveToSession) {
                $_SESSION[$key] = $value;
            }
        }
    }

    public static function removeContextByKey(string $key): void
    {
        unset(self::$_context[$key], $_SESSION[$key]);
    }
}
