<?php

namespace Controllers;

abstract class PublicController implements IController
{
    protected string $name;

    public function __construct()
    {
        $this->name = get_class($this);
        \Utilities\Nav::setPublicNavContext();

        // Upgrade to private layout if the user is logged in
        if (\Utilities\Security::isLogged()) {
            $layout = \Utilities\Context::getContextByKey('PRIVATE_LAYOUT');
            if ($layout !== '') {
                \Utilities\Context::setContext('layoutFile', $layout);
                \Utilities\Nav::setNavContext();
            }
        }
    }

    protected function isPostBack(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
