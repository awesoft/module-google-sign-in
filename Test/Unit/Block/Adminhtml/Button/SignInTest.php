<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Block\Adminhtml\Button;

use Awesoft\GoogleSignIn\Api\Block\Adminhtml\Button\SignInInterface;
use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Awesoft\GoogleSignIn\Block\Adminhtml\Button\SignIn;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SignInTest extends TestCase
{
    /** @var RequestInterface|MockObject $requestMock */
    private RequestInterface|MockObject $requestMock;

    /** @var Repository|MockObject $assetRepoMock */
    private Repository|MockObject $assetRepoMock;

    /** @var ConfigInterface|MockObject $configMock */
    private ConfigInterface|MockObject $configMock;

    /** @var SignInInterface $signIn */
    private SignInInterface $signIn;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->configMock = $this->createMock(ConfigInterface::class);
        $contextMock = $this->createMock(Context::class);
        $this->assetRepoMock = $this->createMock(Repository::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $contextMock->expects($this->once())->method('getRequest')->willReturn($this->requestMock);
        $contextMock->expects($this->once())->method('getAssetRepository')->willReturn($this->assetRepoMock);
        $this->signIn = new SignIn($this->configMock, $contextMock);
    }

    /**
     * @return void
     */
    public function testIsEnabled(): void
    {
        $this->configMock->expects($this->once())->method('isEnabled')->willReturn(true);
        $this->assertSame(true, $this->signIn->isEnabled());
    }

    /**
     * @return void
     */
    public function testGetLoginUrl(): void
    {
        $url = 'https://mage.url/login';
        $this->configMock->expects($this->once())->method('getLoginUrl')->willReturn($url);
        $this->assertSame($url, $this->signIn->getLoginUrl());
    }

    /**
     * @return void
     */
    public function testGetImageUrl(): void
    {
        $url = 'https://mage.url/image.png';
        $this->requestMock->expects($this->once())->method('isSecure')->willReturn(true);
        $this->assetRepoMock->expects($this->once())->method('getUrlWithParams')->willReturn($url);
        $this->assertSame($url, $this->signIn->getImageUrl());
    }
}
