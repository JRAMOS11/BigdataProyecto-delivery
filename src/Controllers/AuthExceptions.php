<?php

namespace Controllers;

/** Thrown when a logged-in user lacks permission for a resource */
class PrivateNoAuthException extends \Exception {}

/** Thrown when a guest tries to access a private resource */
class PrivateNoLoggedException extends \Exception {}
