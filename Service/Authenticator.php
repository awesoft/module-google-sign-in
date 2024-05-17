<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Service;

use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Awesoft\GoogleSignIn\Api\Model\StateInterface;
use Awesoft\GoogleSignIn\Api\Service\AdminUserInterface;
use Awesoft\GoogleSignIn\Api\Service\AuthenticatorInterface;
use Awesoft\GoogleSignIn\Exception\IncorrectAuthenticationException;
use Awesoft\GoogleSignIn\Exception\PermissionAuthenticationException;
use Awesoft\GoogleSignIn\Model\GoogleClient;
use Google\Service\Exception;
use Magento\Backend\Model\Auth\StorageInterface;
use Magento\TwoFactorAuth\Model\TfaSession;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class Authenticator implements AuthenticatorInterface
{
    /**
     * Authentication service constructor.
     *
     * @param GoogleClient $googleClient
     * @param LoggerInterface $logger
     * @param AdminUserInterface $adminUser
     * @param StorageInterface $session
     * @param ConfigInterface $config
     * @param StateInterface $state
     * @param TfaSession $tfaSession
     */
    public function __construct(
        private readonly GoogleClient $googleClient,
        private readonly LoggerInterface $logger,
        private readonly AdminUserInterface $adminUser,
        private readonly StorageInterface $session,
        private readonly ConfigInterface $config,
        private readonly StateInterface $state,
        private readonly TfaSession $tfaSession
    ) {
    }

    /**
     * Get authentication url
     *
     * @return string
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
     * @throws Exception
     * @throws IncorrectAuthenticationException
     * @throws PermissionAuthenticationException
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
        $this->tfaSession?->grantAccess();
    }
}
