<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model;

use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Awesoft\GoogleSignIn\Api\Model\GoogleClientInterface;
use Awesoft\GoogleSignIn\Exception\IncorrectAuthenticationException;
use Awesoft\GoogleSignIn\Exception\PermissionAuthenticationException;
use Google\Client;
use Google\Service\Exception;
use Google\Service\Oauth2;
use Google\Service\Oauth2\Userinfo;
use Psr\Log\LoggerInterface;

class GoogleClient implements GoogleClientInterface
{
    /**
     * Google client constructor.
     *
     * @param LoggerInterface $logger
     * @param Client $client
     * @param ConfigInterface $config
     * @param Oauth2|null $oauth2
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Client $client,
        private readonly ConfigInterface $config,
        private ?Oauth2 $oauth2 = null,
    ) {
        $this->client->setClientId($this->config->getClientId());
        $this->client->setClientSecret($this->config->getClientSecret());
        $this->client->setRedirectUri($this->config->getRedirectUrl());
        $this->oauth2 = $this->oauth2 ?: new Oauth2($this->client);
    }

    /**
     * Get Google authentication url
     *
     * @param string $state
     * @return string
     */
    public function getAuthUrl(string $state): string
    {
        $this->client->setState($state);

        return $this->client->createAuthUrl(['email', 'profile', 'openid']);
    }

    /**
     * Get user info from Google
     *
     * @param string $code
     * @return Userinfo
     * @throws IncorrectAuthenticationException
     * @throws PermissionAuthenticationException
     * @throws Exception
     */
    public function getUserinfo(string $code): Userinfo
    {
        $this->client->fetchAccessTokenWithAuthCode($code);
        $userInfo = $this->oauth2->userinfo_v2_me->get();

        if ($userInfo instanceof Userinfo === false) {
            $this->logger->error('Google userinfo fetch failed');
            throw new IncorrectAuthenticationException();
        }

        $hostedDomains = $this->config->getHostedDomains();
        $hostedDomain = $userInfo->getHd();

        if ($hostedDomains && in_array($hostedDomain, $hostedDomains) === false) {
            $this->logger->error('Hosted domain not allowed', ['hd' => $hostedDomain, 'allowed' => $hostedDomains]);
            throw new PermissionAuthenticationException();
        }

        return $userInfo;
    }
}
