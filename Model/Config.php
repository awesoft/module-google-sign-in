<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model;

use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Config implements ConfigInterface
{
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
