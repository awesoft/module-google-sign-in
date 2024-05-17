<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model;

use Awesoft\GoogleSignIn\Api\Model\UserInterface;
use Awesoft\GoogleSignIn\Model\ResourceModel\User as UserResourceModel;
use Google\Service\Oauth2\Userinfo;
use Magento\User\Model\User as UserModel;

class User extends UserModel implements UserInterface
{
    /**
     * Initialize user model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(UserResourceModel::class);
    }

    /**
     * Set user data from Google userinfo
     *
     * @param Userinfo $userinfo
     * @param string $password
     * @param int $roleId
     * @return $this
     */
    public function withUserinfoData(Userinfo $userinfo, string $password, int $roleId): self
    {
        $this->setData([
            'is_active' => true,
            'role_id' => $roleId,
            'password' => $password,
            'email' => $userinfo->getEmail(),
            'username' => $userinfo->getEmail(),
            'firstname' => $userinfo->getGivenName(),
            'lastname' => $userinfo->getFamilyName(),
        ]);

        return $this;
    }
}
