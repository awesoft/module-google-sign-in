<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Exception;

use Exception;
use Magento\Framework\Exception\AuthenticationException as BaseAuthenticationException;
use Magento\Framework\Phrase;

class AuthenticationException extends BaseAuthenticationException
{
    /**
     * Authentication constructor.
     *
     * @param Phrase|null $phrase
     * @param Exception|null $cause
     * @param int $code
     */
    public function __construct(Phrase $phrase = null, Exception $cause = null, $code = 0)
    {
        if (!$phrase) {
            $phrase = __('An authentication error occurred. Verify and try again.');
        }

        parent::__construct($phrase, $cause, $code);
    }
}
