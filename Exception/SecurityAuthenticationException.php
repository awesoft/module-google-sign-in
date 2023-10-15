<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Exception;

use Exception;

class SecurityAuthenticationException extends AuthenticationException
{
    /**
     * SecurityAuthenticationException constructor.
     *
     * @param Exception|null $cause
     * @param int $code
     */
    public function __construct(Exception $cause = null, $code = 0)
    {
        parent::__construct(__('Invalid security or form key. Please refresh the page.'), $cause, $code);
    }
}
