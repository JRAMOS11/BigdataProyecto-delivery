<?php

namespace Dao;

class Dao
{
    private static ?\PDO $_conn = null;

    private function __construct() {}
    private function __clone() {}

    public static function getConn(): \PDO
    {
        if (self::$_conn !== null) {
            return self::$_conn;
        }

        $dsn = sprintf(
            '%s:host=%s;dbname=%s;port=%s;charset=utf8mb4',
            \Utilities\Context::getContextByKey('DB_PROVIDER'),
            \Utilities\Context::getContextByKey('DB_SERVER'),
            \Utilities\Context::getContextByKey('DB_DATABASE'),
            \Utilities\Context::getContextByKey('DB_PORT')
        );

        self::$_conn = new \PDO(
            $dsn,
            \Utilities\Context::getContextByKey('DB_USER'),
            \Utilities\Context::getContextByKey('DB_PSWD'),
            [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE          => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]
        );

        return self::$_conn;
    }
}
