<?php

namespace Controllers;

class Error extends PublicController
{
    public function run(): void
    {
        $code = \Utilities\Context::getContextByKey('ERROR_CODE') ?: 404;
        $code = (int) $code;

        $messages = [
            404 => 'The requested resource was not found.',
            403 => 'You are not authorized to view this page.',
            500 => 'An unexpected server error occurred.',
        ];

        $msg = $messages[$code] ?? 'Something went wrong.';

        http_response_code($code);
        \Views\Renderer::render('error', [
            'CLIENT_ERROR_CODE' => $code,
            'CLIENT_ERROR_MSG'  => $msg,
        ]);
    }
}
