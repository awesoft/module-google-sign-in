<?php

namespace Awesoft\GoogleSignIn\Api\Model\ResourceModel\User;

interface CollectionInterface
{
    /**
     * @param string $username
     * @return CollectionInterface
     */
    public function loadByUsername(string $username): CollectionInterface;

    /**
     * @param string $email
     * @return CollectionInterface
     */
    public function loadByEmail(string $email): CollectionInterface;

    /**
     * @param int $userId
     * @return CollectionInterface
     */
    public function loadById(int $userId): CollectionInterface;
}
