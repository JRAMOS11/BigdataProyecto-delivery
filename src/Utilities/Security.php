<?php

namespace Utilities;

class Security
{
    private function __construct() {}
    private function __clone() {}

    public static function login(
        int $userId,
        string $userName,
        string $userEmail,
        string $userRol
    ): void {
        session_regenerate_id(true);

        $_SESSION["isLogged"] = true;
        $_SESSION["userId"] = $userId;
        $_SESSION["userName"] = $userName;
        $_SESSION["userEmail"] = $userEmail;
        $_SESSION["userRole"] = $userRol;
    }

    public static function logout(): void
    {
        unset($_SESSION["isLogged"]);
        unset($_SESSION["userId"]);
        unset($_SESSION["userName"]);
        unset($_SESSION["userEmail"]);
        unset($_SESSION["userRole"]);
        unset($_SESSION["user"]);
    }

    public static function isLogged(): bool
    {
        return (
            isset($_SESSION["isLogged"]) &&
            $_SESSION["isLogged"] === true &&
            !empty($_SESSION["userId"]) &&
            !empty($_SESSION["userName"]) &&
            !empty($_SESSION["userEmail"]) &&
            !empty($_SESSION["userRole"])
        );
    }

    public static function getUserRole(): string
    {
        return $_SESSION["userRole"] ?? "";
    }

    public static function getUser(): array|false
    {
        if (!self::isLogged()) {
            return false;
        }

        return [
            "userId" => $_SESSION["userId"],
            "userName" => $_SESSION["userName"],
            "userEmail" => $_SESSION["userEmail"],
            "userRole" => $_SESSION["userRole"],
        ];
    }

    public static function getUserId(): int
    {
        return (int)($_SESSION["userId"] ?? 0);
    }
}
