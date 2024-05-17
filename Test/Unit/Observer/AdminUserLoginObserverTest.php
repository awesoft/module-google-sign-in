<?php

namespace Awesoft\GoogleSignIn\Test\Unit\Observer;

use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Awesoft\GoogleSignIn\Observer\AdminUserLoginObserver;
use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\Plugin\AuthenticationException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AdminUserLoginObserverTest extends TestCase
{
    /** @var ConfigInterface|MockObject $configMock */
    private ConfigInterface|MockObject $configMock;

    /** @var Observer|MockObject $observerMock */
    private Observer|MockObject $observerMock;

    /** @var ObserverInterface $adminUserLoginObserver */
    private ObserverInterface $adminUserLoginObserver;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->observerMock = $this->createMock(Observer::class);
        $this->configMock = $this->createMock(ConfigInterface::class);
        $this->adminUserLoginObserver = new AdminUserLoginObserver($this->configMock);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testExecute(): void
    {
        $this->configMock->expects($this->once())->method('isDisableLoginForm')->willReturn(false);
        $this->adminUserLoginObserver->execute($this->observerMock);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testExecuteWithException(): void
    {
        $this->configMock->expects($this->once())->method('isDisableLoginForm')->willReturn(true);

        $this->expectException(AuthenticationException::class);
        $this->adminUserLoginObserver->execute($this->observerMock);
    }
}
