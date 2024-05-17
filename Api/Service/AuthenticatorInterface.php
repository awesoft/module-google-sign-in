<?php

namespace Awesoft\GoogleSignIn\Api\Service;

interface AuthenticatorInterface
{
    /**
     * @return string
     */
    public function getAuthUrl(): string;

    /**
     * @param string $code
     * @param string $state
     * @return void
     */
    public function authenticate(string $code, string $state): void;
}
