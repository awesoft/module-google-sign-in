<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Model;

use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Awesoft\GoogleSignIn\Exception\IncorrectAuthenticationException;
use Awesoft\GoogleSignIn\Exception\PermissionAuthenticationException;
use Awesoft\GoogleSignIn\Model\GoogleClient;
use Google\Client;
use Google\Service\Exception;
use Google\Service\Oauth2;
use Google\Service\Oauth2\Resource\UserinfoV2Me;
use Google\Service\Oauth2\Userinfo;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GoogleClientTest extends TestCase
{
    /** @var LoggerInterface|MockObject $loggerMock */
    private LoggerInterface|MockObject $loggerMock;

    /** @var Userinfo|MockObject $userinfoMock */
    private Userinfo|MockObject $userinfoMock;

    /** @var ClientInterface|MockObject $clientMock */
    private ClientInterface|MockObject $clientMock;

    /** @var ConfigInterface|MockObject $configMock */
    private ConfigInterface|MockObject $configMock;

    /** @var Oauth2|MockObject $oauth2Mock */
    private Oauth2|MockObject $oauth2Mock;

    /** @var GoogleClient $googleClient */
    private GoogleClient $googleClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->configMock = $this->createMock(ConfigInterface::class);
        $this->oauth2Mock = $this->createMock(Oauth2::class);
        $this->userinfoMock = $this->createMock(Userinfo::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->oauth2Mock->userinfo_v2_me = $this->createMock(UserinfoV2Me::class);

        $this->googleClient = new GoogleClient(
            $this->loggerMock,
            $this->clientMock,
            $this->configMock,
            $this->oauth2Mock,
        );
    }

    /**
     * @return void
     */
    public function testGetAuthUrl(): void
    {
        $url = 'https://google.url/oauth2';
        $this->clientMock->expects($this->once())->method('setState')->with('state');
        $this->clientMock->expects($this->once())->method('createAuthUrl')->willReturn($url);
        $this->assertSame($url, $this->googleClient->getAuthUrl('state'));
    }

    /**
     * @return void
     * @throws IncorrectAuthenticationException
     * @throws PermissionAuthenticationException
     * @throws Exception
     */
    public function testGetUserinfo(): void
    {
        $this->oauth2Mock->userinfo_v2_me->expects($this->once())->method('get')->willReturn($this->userinfoMock);
        $this->clientMock->expects($this->once())->method('fetchAccessTokenWithAuthCode')->with('code');
        $this->configMock->expects($this->once())->method('getHostedDomains')->willReturn([]);
        $this->assertSame($this->userinfoMock, $this->googleClient->getUserinfo('code'));
    }

    /**
     * @return void
     * @throws Exception
     * @throws IncorrectAuthenticationException
     * @throws PermissionAuthenticationException
     */
    public function testGetUserinfoFetchFailed(): void
    {
        $this->clientMock->expects($this->once())->method('fetchAccessTokenWithAuthCode')->with('code');
        $this->oauth2Mock->userinfo_v2_me->expects($this->once())->method('get')->willReturn(null);
        $this->loggerMock->expects($this->once())->method('error');
        $this->expectException(IncorrectAuthenticationException::class);
        $this->googleClient->getUserinfo('code');
    }

    /**
     * @return void
     * @throws Exception
     * @throws IncorrectAuthenticationException
     * @throws PermissionAuthenticationException
     */
    public function testGetUserinfoNotAllowedHostedDomain(): void
    {
        $this->oauth2Mock->userinfo_v2_me->expects($this->once())->method('get')->willReturn($this->userinfoMock);
        $this->configMock->expects($this->once())->method('getHostedDomains')->willReturn(['example.com']);
        $this->clientMock->expects($this->once())->method('fetchAccessTokenWithAuthCode')->with('code');
        $this->userinfoMock->expects($this->once())->method('getHd')->willReturn('example.net');
        $this->loggerMock->expects($this->once())->method('error');
        $this->expectException(PermissionAuthenticationException::class);
        $this->googleClient->getUserinfo('code');
    }
}
