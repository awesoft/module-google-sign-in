<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Model;

use Awesoft\GoogleSignIn\Model\Config;
use Exception;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Serialize\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private ScopeConfigInterface|MockObject $scopeConfigMock;
    private SerializerInterface|MockObject $serializerMock;
    private EncryptorInterface|MockObject $encryptorMock;
    private UrlInterface|MockObject $urlMock;
    private Config $config;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->serializerMock = $this->createMock(SerializerInterface::class);
        $this->encryptorMock = $this->createMock(EncryptorInterface::class);
        $this->urlMock = $this->createMock(UrlInterface::class);
        $this->config = new Config(
            $this->scopeConfigMock,
            $this->serializerMock,
            $this->encryptorMock,
            $this->urlMock,
        );
    }

    /**
     * @return void
     */
    public function testIsEnabled(): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_IS_ENABLED)
            ->willReturn('1');

        $this->assertSame(true, $this->config->isEnabled());
    }

    /**
     * @return void
     */
    public function testGetClientId(): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_CLIENT_ID)
            ->willReturn('client_id');

        $this->assertSame('client_id', $this->config->getClientId());
    }

    /**
     * @return void
     */
    public function testGetClientSecret(): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_CLIENT_SECRET)
            ->willReturn('encrypted_client_secret');
        $this->encryptorMock->expects($this->once())
            ->method('decrypt')
            ->with('encrypted_client_secret')
            ->willReturn('client_secret');

        $this->assertSame('client_secret', $this->config->getClientSecret());
    }

    /**
     * @return void
     */
    public function testGetHostedDomains(): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_HOSTED_DOMAINS)
            ->willReturn('["example.com"]');
        $this->serializerMock->expects($this->once())
            ->method('unserialize')
            ->with('["example.com"]')
            ->willReturn(['example.com']);

        $this->assertSame(['example.com'], $this->config->getHostedDomains());
    }

    /**
     * @return void
     */
    public function testGetRedirectUrl(): void
    {
        $url = 'https://awesoft.dev/google-signin/verify';
        $this->urlMock->expects($this->once())->method('turnOffSecretKey')->willReturnSelf();
        $this->urlMock->expects($this->once())
            ->method('getRouteUrl')
            ->with(Config::URL_PATH_REDIRECT)
            ->willReturn($url);

        $this->assertSame($url, $this->config->getRedirectUrl());
    }

    /**
     * @return void
     */
    public function testGetLoginUrl(): void
    {
        $url = 'https://awesoft.dev/google-signin/index';
        $this->urlMock->expects($this->once())->method('turnOffSecretKey')->willReturnSelf();
        $this->urlMock->expects($this->once())
            ->method('getRouteUrl')
            ->with(Config::URL_PATH_LOGIN)
            ->willReturn($url);

        $this->assertSame($url, $this->config->getLoginUrl());
    }

    /**
     * @return void
     */
    public function testIsUserCreateEnabled(): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_ENABLE_USER_CREATE)
            ->willReturn('1');

        $this->assertSame(true, $this->config->isUserCreateEnabled());
    }

    /**
     * @return void
     */
    public function testGetRoleId(): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->with(Config::XML_PATH_ROLE_ID)
            ->willReturn('2');

        $this->assertSame(2, $this->config->getRoleId());
    }

    /**
     * @return array
     */
    public function canBypassTfaDataProvider(): array
    {
        return [
            [true, 2, true, true],
            [false, 2, true, false],
            [false, 1, false, true],
            [false, 1, false, false],
        ];
    }
}
