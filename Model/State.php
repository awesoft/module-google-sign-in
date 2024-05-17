<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model;

use Awesoft\GoogleSignIn\Api\Model\StateInterface;
use Awesoft\GoogleSignIn\Exception\SecurityAuthenticationException;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class State implements StateInterface
{
    /**
     * @param LoggerInterface $logger
     * @param Session $storage
     * @param Random $random
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Session $storage,
        private readonly Random $random,
    ) {
    }

    /**
     * Generate random state
     *
     * @throws LocalizedException
     */
    public function generate(): string
    {
        $state = $this->random->getRandomString(self::LENGTH);
        $this->storage->setData(self::KEY, $state);

        return $state;
    }

    /**
     * Get generated state from session.
     *
     * @return string
     */
    public function getData(): string
    {
        $state = $this->storage->getData(self::KEY);
        $this->storage->unsData(self::KEY);

        return $state;
    }

    /**
     * Validate state against saved from session
     *
     * @param string $state
     * @return void
     * @throws SecurityAuthenticationException
     */
    public function validate(string $state): void
    {
        if ($this->getData() !== $state) {
            $this->logger->error('Google sign-in authentication state validation failed', ['state' => $state]);
            throw new SecurityAuthenticationException();
        }
    }
}
