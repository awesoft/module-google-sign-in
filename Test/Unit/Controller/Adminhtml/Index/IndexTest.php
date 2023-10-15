<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Controller\Adminhtml\Index;

use Awesoft\GoogleSignIn\Controller\Adminhtml\Index\Index;
use Awesoft\GoogleSignIn\Service\Authenticator;
use Magento\Backend\Model\Auth;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    /**
     * @dataProvider executeDataProvider
     * @param bool $isLoggedIn
     * @param int $getAuthUrl
     * @return void
     * @throws LocalizedException
     */
    public function testExecute(bool $isLoggedIn, int $getAuthUrl): void
    {
        $authenticatorServiceMock = $this->createMock(Authenticator::class);
        $authenticatorServiceMock->expects($this->exactly($getAuthUrl))
            ->method('getAuthUrl')
            ->willReturn('https://auth.url/');

        $redirectMock = $this->createMock(Redirect::class);
        $redirectMock->expects($this->once())
            ->method('setPath')
            ->willReturnSelf();

        $redirectFactoryMock = $this->createMock(RedirectFactory::class);
        $redirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($redirectMock);

        $authMock = $this->createMock(Auth::class);
        $authMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn($isLoggedIn);

        $this->assertSame(
            $redirectMock,
            (new Index(
                $authenticatorServiceMock,
                $redirectFactoryMock,
                $authMock
            ))->execute()
        );
    }

    /**
     * @return array[]
     */
    public function executeDataProvider(): array
    {
        return [
            [true, 0],
            [false, 1],
        ];
    }
}
