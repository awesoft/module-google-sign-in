<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Exception;

use Exception;

class PermissionAuthenticationException extends AuthenticationException
{
    /**
     * PermissionAuthenticationException constructor.
     *
     * @param Exception|null $cause
     * @param int $code
     */
    public function __construct(Exception $cause = null, $code = 0)
    {
        parent::__construct(__('More permissions are needed to access this.'), $cause, $code);
    }
}
