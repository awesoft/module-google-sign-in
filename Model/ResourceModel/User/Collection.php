<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model\ResourceModel\User;

use Awesoft\GoogleSignIn\Model\ResourceModel\User as UserResourceModel;
use Awesoft\GoogleSignIn\Model\User as UserModel;
use Magento\User\Model\ResourceModel\User\Collection as UserCollection;

class Collection extends UserCollection
{
    /**
     * Initialize user collection model.
     */
    protected function _construct(): void
    {
        $this->_init(UserModel::class, UserResourceModel::class);
    }

    /**
     * Load user by username
     *
     * @param string $username
     * @return $this
     */
    public function loadByUsername(string $username): self
    {
        $this->getSelect()->limit(1);
        $this->addFieldToFilter('is_active', true);
        $this->addFieldToFilter('username', $username);

        return $this;
    }

    /**
     * Log user by email
     *
     * @param string $email
     * @return $this
     */
    public function loadByEmail(string $email): self
    {
        $this->getSelect()->limit(1);
        $this->addFieldToFilter('is_active', true);
        $this->addFieldToFilter('email', $email);

        return $this;
    }

    /**
     * Load user by id
     *
     * @param int $userId
     * @return $this
     */
    public function loadById(int $userId): self
    {
        $this->getSelect()->limit(1);
        $this->addFieldToFilter('is_active', true);
        $this->addFieldToFilter('user_id', $userId);

        return $this;
    }
}
