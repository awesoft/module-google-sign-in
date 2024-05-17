<?php

namespace Awesoft\GoogleSignIn\Api\Model\ResourceModel;

use Awesoft\GoogleSignIn\Model\User as UserModel;

interface UserInterface
{
    /**
     * @param UserModel $userModel
     * @return void
     */
    public function saveUser(UserModel $userModel): void;
}
