<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Service;

use Awesoft\GoogleSignIn\Exception\AuthenticationException;
use Awesoft\GoogleSignIn\Exception\IncorrectAuthenticationException;
use Awesoft\GoogleSignIn\Model\Config;
use Awesoft\GoogleSignIn\Model\GoogleClient;
use Awesoft\GoogleSignIn\Model\State;
use Awesoft\GoogleSignIn\Model\User;
use Awesoft\GoogleSignIn\Service\AdminUser;
use Awesoft\GoogleSignIn\Service\Authenticator;
use Google\Service\Oauth2\Userinfo;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\TwoFactorAuth\Model\TfaSession;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AuthenticatorTest extends TestCase
{
    /** @var GoogleClient|MockObject $googleClientMock */
    private GoogleClient|MockObject $googleClientMock;

    /** @var AdminUser|MockObject $adminUserMock */
    private AdminUser|MockObject $adminUserMock;

    /** @var LoggerInterface|MockObject $loggerMock */
    private LoggerInterface|MockObject $loggerMock;

    /** @var TfaSession|MockObject $tfaSessionMock */
    private TfaSession|MockObject $tfaSessionMock;

    /** @var Session|MockObject $sessionMock */
    private Session|MockObject $sessionMock;

    /** @var Config|MockObject $configMock */
    private Config|MockObject $configMock;

    /** @var State|MockObject $stateMock */
    private State|MockObject $stateMock;

    /** @var Userinfo|MockObject $userinfoMock */
    private Userinfo|MockObject $userinfoMock;

    /** @var User|MockObject $userMock */
    private User|MockObject $userMock;

    /** @var Authenticator|MockObject $authenticator */
    private Authenticator|MockObject $authenticator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->googleClientMock = $this->createMock(GoogleClient::class);
        $this->adminUserMock = $this->createMock(AdminUser::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->tfaSessionMock = $this->createMock(TfaSession::class);
        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['processLogin'])
            ->addMethods(['setUser'])
            ->getMock();
        $this->configMock = $this->createMock(Config::class);
        $this->stateMock = $this->createMock(State::class);
        $this->userinfoMock = $this->createMock(Userinfo::class);
        $this->userMock = $this->createMock(User::class);
        $this->authenticator = new Authenticator(
            $this->googleClientMock,
            $this->loggerMock,
            $this->adminUserMock,
            $this->sessionMock,
            $this->configMock,
            $this->stateMock,
            $this->tfaSessionMock,
        );
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function testGetAuthUrl(): void
    {
        $url = 'https://google.com/auth/url?state=state';
        $this->stateMock->expects($this->once())->method('generate')->willReturn('state');
        $this->googleClientMock->expects($this->once())->method('getAuthUrl')->willReturn($url);
        $this->assertSame($url, $this->authenticator->getAuthUrl());
    }

    /**
     * @dataProvider authenticateDataProvider
     * @param array $userIds
     * @param bool $userCreateEnabled
     * @param int $createUser
     * @param int $info
     * @param int $error
     * @param int $proceed
     * @param int $grantAccess
     * @return void
     * @throws AuthenticationException
     * @throws LocalizedException
     */
    public function testAuthenticate(
        array $userIds,
        bool $userCreateEnabled,
        int $createUser,
        int $info,
        int $error,
        int $proceed,
        int $grantAccess,
    ): void {
        $this->stateMock->expects($this->once())->method('validate');
        $this->googleClientMock->expects($this->once())
            ->method('getUserinfo')
            ->willReturn($this->userinfoMock);
        $this->configMock->expects($this->once())
            ->method('isUserCreateEnabled')
            ->willReturn($userCreateEnabled);
        $this->adminUserMock->expects($this->once())
            ->method('loadByUserinfo')
            ->willReturn($this->userMock);
        $this->adminUserMock->expects($this->exactly($createUser))
            ->method('createUser')
            ->willReturn($this->userMock);
        $this->userMock->expects($this->exactly(count($userIds)))
            ->method('getId')
            ->willReturnOnConsecutiveCalls(...$userIds);
        $this->loggerMock->expects($this->exactly($info))->method('info');
        $this->loggerMock->expects($this->exactly($error))->method('error');
        $this->sessionMock->expects($this->exactly($proceed))->method('setUser');
        $this->sessionMock->expects($this->exactly($proceed))->method('processLogin');
        $this->tfaSessionMock->expects($this->exactly($grantAccess))->method('grantAccess');
        $this->authenticator->authenticate('code', 'state');
    }

    /**
     * @return array
     */
    public function authenticateDataProvider(): array
    {
        return [
            [[null, 9, 9], true, 1, 1, 0, 1, 1],
            [[9, 9], true, 0, 0, 0, 1, 1],
            [[9, 9], false, 0, 0, 0, 1, 1],
        ];
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function testAuthenticateUserNotFound(): void
    {
        $this->stateMock->expects($this->once())->method('validate');
        $this->loggerMock->expects($this->once())->method('error');
        $this->userinfoMock->expects($this->once())
            ->method('getEmail')
            ->willReturn('user@example.com');
        $this->googleClientMock->expects($this->once())
            ->method('getUserinfo')
            ->willReturn($this->userinfoMock);
        $this->configMock->expects($this->once())
            ->method('isUserCreateEnabled')
            ->willReturn(false);
        $this->userMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturnOnConsecutiveCalls(null, null);
        $this->adminUserMock->expects($this->once())
            ->method('loadByUserinfo')
            ->willReturn($this->userMock);
        $this->expectException(IncorrectAuthenticationException::class);
        $this->authenticator->authenticate('code', 'state');
    }
}
