<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Service;

use Awesoft\GoogleSignIn\Exception\AuthenticationException;
use Awesoft\GoogleSignIn\Exception\IncorrectAuthenticationException;
use Awesoft\GoogleSignIn\Model\Config;
use Awesoft\GoogleSignIn\Model\GoogleClient;
use Awesoft\GoogleSignIn\Model\State;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\TwoFactorAuth\Model\TfaSession;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class Authenticator
{
    /**
     * Authentication service constructor.
     *
     * @param GoogleClient $googleClient
     * @param LoggerInterface $logger
     * @param TfaSession $tfaSession
     * @param AdminUser $adminUser
     * @param Session $session
     * @param Config $config
     * @param State $state
     */
    public function __construct(
        private readonly GoogleClient $googleClient,
        private readonly LoggerInterface $logger,
        private readonly TfaSession $tfaSession,
        private readonly AdminUser $adminUser,
        private readonly Session $session,
        private readonly Config $config,
        private readonly State $state,
    ) {
    }

    /**
     * Get authentication url
     *
     * @return string
     * @throws LocalizedException
     */
    public function getAuthUrl(): string
    {
        return $this->googleClient->getAuthUrl($this->state->generate());
    }

    /**
     * Authenticate user from Google code and state
     *
     * @param string $code
     * @param string $state
     * @return void
     * @throws AuthenticationException
     * @throws LocalizedException
     */
    public function authenticate(string $code, string $state): void
    {
        $this->state->validate($state);
        $userinfo = $this->googleClient->getUserinfo($code);
        $user = $this->adminUser->loadByUserinfo($userinfo);
        $isUserCreateEnabled = $this->config->isUserCreateEnabled();

        if ((!$user || !$user->getId()) && $isUserCreateEnabled) {
            $user = $this->adminUser->createUser($userinfo);
            $this->logger->info('Admin user created', ['userId' => $user->getId()]);
        }

        if (!$user || !$user->getId()) {
            $this->logger->error('Admin user not found', ['email' => $userinfo->getEmail()]);
            throw new IncorrectAuthenticationException();
        }

        $this->session->setUser($user);
        $this->session->processLogin();
        $this->tfaSession->grantAccess();
    }
}
