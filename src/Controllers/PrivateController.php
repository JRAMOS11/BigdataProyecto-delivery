<?php

namespace Controllers;

abstract class PrivateController implements IController
{
    protected string $name;

    public function __construct()
    {
        $this->name = get_class($this);

        if (!\Utilities\Security::isLogged()) {
            throw new PrivateNoLoggedException();
        }

        \Utilities\Nav::setNavContext();

        $layout = \Utilities\Context::getContextByKey('PRIVATE_LAYOUT');
        if ($layout !== '') {
            \Utilities\Context::setContext('layoutFile', $layout);
        }
    }

    protected function isPostBack(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function requireAuth(bool $condition): void
    {
        if (!$condition) {
            throw new PrivateNoAuthException();
        }
    }
}
