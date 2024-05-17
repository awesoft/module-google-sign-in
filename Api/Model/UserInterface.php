<?php

namespace Awesoft\GoogleSignIn\Api\Model;

use Google\Service\Oauth2\Userinfo;

interface UserInterface
{
    /**
     * @param Userinfo $userinfo
     * @param string $password
     * @param int $roleId
     * @return UserInterface
     */
    public function withUserinfoData(Userinfo $userinfo, string $password, int $roleId): UserInterface;
}
