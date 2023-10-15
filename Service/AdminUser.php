<?php /** @noinspection PhpIncompatibleReturnTypeInspection */

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Service;

use Awesoft\GoogleSignIn\Model\Config;
use Awesoft\GoogleSignIn\Model\ResourceModel\User as UserResourceModel;
use Awesoft\GoogleSignIn\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
use Awesoft\GoogleSignIn\Model\User;
use Awesoft\GoogleSignIn\Model\UserFactory;
use Google\Service\Oauth2\Userinfo;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;

class AdminUser
{
    private const PASSWORD_LENGTH = 30;

    /**
     * AdminUser constructor.
     *
     * @param UserCollectionFactory $userCollectionFactory
     * @param UserResourceModel $userResourceModel
     * @param UserFactory $userFactory
     * @param Config $config
     * @param Random $random
     */
    public function __construct(
        private readonly UserCollectionFactory $userCollectionFactory,
        private readonly UserResourceModel $userResourceModel,
        private readonly UserFactory $userFactory,
        private readonly Config $config,
        private readonly Random $random,
    ) {
    }

    /**
     * Load admin user by Google userinfo
     *
     * @param Userinfo $userinfo
     * @return User|null
     */
    public function loadByUserinfo(Userinfo $userinfo): ?User
    {
        $email = $userinfo->getEmail();
        $user = $this->userCollectionFactory->create()->loadByUsername($email)->getFirstItem();

        if ($user && $user->getId()) {
            return $user;
        }

        return $this->userCollectionFactory->create()->loadByEmail($email)->getFirstItem();
    }

    /**
     * Get admin user by id
     *
     * @param int $userId
     * @return User|null
     */
    public function loadById(int $userId): ?User
    {
        return $this->userCollectionFactory->create()->loadById($userId)->getFirstItem();
    }

    /**
     * Get admin user from Google userinfo
     *
     * @param Userinfo $userinfo
     * @return User|null
     * @throws AlreadyExistsException
     * @throws LocalizedException
     */
    public function createUser(Userinfo $userinfo): ?User
    {
        $roleId = $this->config->getRoleId();
        $password = $this->random->getRandomString(self::PASSWORD_LENGTH);
        $user = $this->userFactory->create()->withUserinfoData($userinfo, $password, $roleId);

        $this->userResourceModel->saveUser($user);

        return $this->loadById((int)$user->getId());
    }
}
