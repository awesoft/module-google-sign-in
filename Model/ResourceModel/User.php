<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model\ResourceModel;

use Awesoft\GoogleSignIn\Api\Model\ResourceModel\UserInterface;
use Awesoft\GoogleSignIn\Model\User as UserModel;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\User\Model\ResourceModel\User as UserResourceModel;

class User extends UserResourceModel implements UserInterface
{
    /**
     * Save user model
     *
     * @param UserModel $userModel
     * @return void
     * @throws AlreadyExistsException
     */
    public function saveUser(UserModel $userModel): void
    {
        $this->save($userModel);
        $this->recordLogin($userModel);
    }
}
