<?php

namespace Awesoft\GoogleSignIn\Api\Model;

use Google\Service\Oauth2\Userinfo;

interface GoogleClientInterface
{
    /**
     * @param string $state
     * @return string
     */
    public function getAuthUrl(string $state): string;

    /**
     * @param string $code
     * @return Userinfo
     */
    public function getUserinfo(string $code): Userinfo;
}
