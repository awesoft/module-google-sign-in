<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Service;

use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Awesoft\GoogleSignIn\Model\ResourceModel\User as UserResourceModel;
use Awesoft\GoogleSignIn\Model\ResourceModel\User\Collection as UserCollection;
use Awesoft\GoogleSignIn\Model\ResourceModel\User\CollectionFactory as UserCollectionFactory;
use Awesoft\GoogleSignIn\Model\User;
use Awesoft\GoogleSignIn\Model\UserFactory;
use Awesoft\GoogleSignIn\Service\AdminUser;
use Google\Service\Oauth2\Userinfo;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AdminUserTest extends TestCase
{
    /** @var UserCollectionFactory|MockObject $userCollectionFactoryMock */
    private UserCollectionFactory|MockObject $userCollectionFactoryMock;

    /** @var UserResourceModel|MockObject $userResourceModelMock */
    private UserResourceModel|MockObject $userResourceModelMock;

    /** @var UserCollection|MockObject $userCollectionMock */
    private UserCollection|MockObject $userCollectionMock;

    /** @var UserFactory|MockObject $userFactoryMock */
    private UserFactory|MockObject $userFactoryMock;

    /** @var Userinfo|MockObject $userinfoMock */
    private Userinfo|MockObject $userinfoMock;

    /** @var ConfigInterface|MockObject $configMock */
    private ConfigInterface|MockObject $configMock;

    /** @var Random|MockObject $randomMock */
    private Random|MockObject $randomMock;

    /** @var User|MockObject $userMock */
    private User|MockObject $userMock;

    /** @var AdminUser $adminUser */
    private AdminUser $adminUser;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->userCollectionFactoryMock = $this->createMock(UserCollectionFactory::class);
        $this->userResourceModelMock = $this->createMock(UserResourceModel::class);
        $this->userCollectionMock = $this->createMock(UserCollection::class);
        $this->userFactoryMock = $this->createMock(UserFactory::class);
        $this->userinfoMock = $this->createMock(Userinfo::class);
        $this->configMock = $this->createMock(ConfigInterface::class);
        $this->randomMock = $this->createMock(Random::class);
        $this->userMock = $this->createMock(User::class);

        $this->adminUser = new AdminUser(
            $this->userCollectionFactoryMock,
            $this->userResourceModelMock,
            $this->userFactoryMock,
            $this->configMock,
            $this->randomMock,
        );
    }

    /**
     * @dataProvider loadByUserinfoDataProvider
     * @param array $userIds
     * @param int $loadByEmail
     * @param int $proceed
     * @return void
     */
    public function testLoadByUserinfoWithUsername(array $userIds, int $loadByEmail, int $proceed): void
    {
        $this->userinfoMock->expects($this->once())
            ->method('getEmail')
            ->willReturn('user@example.com');
        $this->userMock->expects($this->exactly(count($userIds)))
            ->method('getId')
            ->willReturnOnConsecutiveCalls(...$userIds);
        $this->userCollectionMock->expects($this->once())
            ->method('loadByUsername')
            ->willReturnSelf();
        $this->userCollectionMock->expects($this->exactly($loadByEmail))
            ->method('loadByEmail')
            ->willReturnSelf();
        $this->userCollectionMock->expects($this->exactly($proceed))
            ->method('getFirstItem')
            ->willReturn($this->userMock);
        $this->userCollectionFactoryMock->expects($this->exactly($proceed))
            ->method('create')
            ->willReturn($this->userCollectionMock);

        $this->adminUser->loadByUserinfo($this->userinfoMock);
    }

    /**
     * @return array[]
     */
    public function loadByUserinfoDataProvider(): array
    {
        return [
            [[9], 0, 1],
            [[null], 1, 2],
        ];
    }

    /**
     * @dataProvider loadByIdDataProvider
     * @param int|null $userId
     * @return void
     */
    public function testLoadById(?int $userId): void
    {
        $this->userMock->expects($this->once())
            ->method('getId')
            ->willReturn($userId);
        $this->userCollectionMock->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($this->userMock);
        $this->userCollectionMock->expects($this->once())
            ->method('loadById')
            ->willReturnSelf();
        $this->userCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->userCollectionMock);
        $user = $this->adminUser->loadById(9);

        $this->assertSame($this->userMock, $user);
        $this->assertSame($userId, $user->getId());
    }

    /**
     * @return array
     */
    public function loadByIdDataProvider(): array
    {
        return [
            [9],
            [null]
        ];
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function testCreateUser(): void
    {
        $userinfoMock = $this->createMock(Userinfo::class);
        $userMock = $this->createMock(User::class);
        $userMock->expects($this->once())
            ->method('withUserinfoData')
            ->willReturnSelf();
        $this->configMock->expects($this->once())
            ->method('getRoleId')
            ->willReturn(5);
        $this->randomMock->expects($this->once())
            ->method('getRandomString')
            ->willReturn('random-string');
        $this->userFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($userMock);
        $this->userResourceModelMock->expects($this->once())
            ->method('saveUser')
            ->with($userMock);
        $this->userCollectionMock->expects($this->once())
            ->method('loadById')
            ->willReturnSelf();
        $this->userCollectionMock->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($userMock);
        $this->userCollectionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->userCollectionMock);
        $this->assertSame($userMock, $this->adminUser->createUser($userinfoMock));
    }
}
