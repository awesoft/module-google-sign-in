<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Exception;

use Exception;

class IncorrectAuthenticationException extends AuthenticationException
{
    /**
     * IncorrectAuthenticatorException constructor.
     *
     * @param Exception|null $cause
     * @param int $code
     */
    public function __construct(Exception $cause = null, $code = 0)
    {
        $phrase = __(
            'The account sign-in was incorrect or your account is disabled temporarily.'
            . ' Please wait and try again later.'
        );

        parent::__construct($phrase, $cause, $code);
    }
}
