<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Config
{
    public const XML_PATH_IS_ENABLED = 'awesoft/google_signin/is_enabled';
    public const XML_PATH_CLIENT_ID = 'awesoft/google_signin/client_id';
    public const XML_PATH_CLIENT_SECRET = 'awesoft/google_signin/client_secret';
    public const XML_PATH_HOSTED_DOMAINS = 'awesoft/google_signin/hosted_domains';
    public const XML_PATH_ENABLE_USER_CREATE = 'awesoft/google_signin/enable_user_create';
    public const XML_PATH_DISABLE_LOGIN_FORM = 'awesoft/google_signin/disable_login_form';
    public const XML_PATH_ROLE_ID = 'awesoft/google_signin/role_id';
    public const URL_PATH_LOGIN = 'awesoft_google_signin/index';
    public const URL_PATH_REDIRECT = 'awesoft_google_signin/verify';

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param SerializerInterface $serializer
     * @param EncryptorInterface $encryptor
     * @param UrlInterface $url
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SerializerInterface $serializer,
        private readonly EncryptorInterface $encryptor,
        private readonly UrlInterface $url,
    ) {
    }

    /**
     * Is feature enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_IS_ENABLED);
    }

    /**
     * Get Google API client ID
     *
     * @return string
     */
    public function getClientId(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_CLIENT_ID);
    }

    /**
     * Get Google API client secret
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->encryptor->decrypt(
            (string)$this->scopeConfig->getValue(self::XML_PATH_CLIENT_SECRET)
        );
    }

    /**
     * Get allowed hosted domains
     *
     * @return array
     */
    public function getHostedDomains(): array
    {
        $domains = [];
        $hostedDomains = (array)$this->serializer->unserialize(
            (string)$this->scopeConfig->getValue(self::XML_PATH_HOSTED_DOMAINS)
        );

        foreach ($hostedDomains as $item) {
            $domains[] = $item['domain'];
        }

        return array_unique($domains);
    }

    /**
     * Get redirect URL
     *
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->url->turnOffSecretKey()->getRouteUrl(self::URL_PATH_REDIRECT);
    }

    /**
     * Get login URL
     *
     * @return string
     */
    public function getLoginUrl(): string
    {
        return $this->url->turnOffSecretKey()->getRouteUrl(self::URL_PATH_LOGIN);
    }

    /**
     * Is user creation enabled
     *
     * @return bool
     */
    public function isUserCreateEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_ENABLE_USER_CREATE);
    }

    /**
     * Get role id
     *
     * @return int
     */
    public function getRoleId(): int
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_ROLE_ID);
    }

    /**
     * @return bool
     */
    public function isDisableLoginForm(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_DISABLE_LOGIN_FORM);
    }
}
