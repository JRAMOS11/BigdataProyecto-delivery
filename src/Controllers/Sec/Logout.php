<?php

namespace Controllers\Sec;

class Logout extends \Controllers\PublicController
{
    public function run(): void
    {
        \Utilities\Security::logout();
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        \Utilities\Site::redirectTo('index.php');
    }
}
