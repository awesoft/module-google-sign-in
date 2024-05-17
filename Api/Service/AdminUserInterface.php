<?php

namespace Awesoft\GoogleSignIn\Api\Service;

use Awesoft\GoogleSignIn\Model\User;
use Google\Service\Oauth2\Userinfo;

interface AdminUserInterface
{
    public const PASSWORD_LENGTH = 30;

    /**
     * @param Userinfo $userinfo
     * @return User|null
     */
    public function loadByUserinfo(Userinfo $userinfo): ?User;

    /**
     * @param int $userId
     * @return User|null
     */
    public function loadById(int $userId): ?User;

    /**
     * @param Userinfo $userinfo
     * @return User|null
     */
    public function createUser(Userinfo $userinfo): ?User;
}
