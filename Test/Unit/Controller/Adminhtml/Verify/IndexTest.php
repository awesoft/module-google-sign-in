<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Controller\Adminhtml\Verify;

use Awesoft\GoogleSignIn\Controller\Adminhtml\Verify\Index;
use Awesoft\GoogleSignIn\Exception\AuthenticationException;
use Awesoft\GoogleSignIn\Service\Authenticator;
use Exception;
use Magento\Backend\Model\Auth;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Throwable;

class IndexTest extends TestCase
{
    /** @var Authenticator|MockObject $authenticatorServiceMock */
    private Authenticator|MockObject $authenticatorServiceMock;

    /** @var RedirectFactory|MockObject $redirectFactoryMock */
    private RedirectFactory|MockObject $redirectFactoryMock;

    /** @var RequestInterface|MockObject $requestMock */
    private RequestInterface|MockObject $requestMock;

    /** @var ManagerInterface|MockObject $managerMock */
    private ManagerInterface|MockObject $managerMock;

    /** @var LoggerInterface|MockObject $loggerMock */
    private LoggerInterface|MockObject $loggerMock;

    /** @var Redirect|MockObject $redirectMock */
    private Redirect|MockObject $redirectMock;

    /** @var Auth|MockObject $authMock */
    private Auth|MockObject $authMock;

    /** @var Index $index */
    private Index $index;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->authenticatorServiceMock = $this->createMock(Authenticator::class);
        $this->redirectFactoryMock = $this->createMock(RedirectFactory::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->managerMock = $this->createMock(ManagerInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->redirectMock = $this->createMock(Redirect::class);
        $this->authMock = $this->createMock(Auth::class);

        $this->redirectMock->expects($this->once())->method('setPath')->willReturnSelf();
        $this->redirectMock->expects($this->exactly(2))->method('setHeader')->willReturnSelf();
        $this->requestMock->expects($this->exactly(2))->method('getParam')->willReturn('param');
        $this->redirectFactoryMock->expects($this->once())->method('create')->willReturn($this->redirectMock);

        $this->index = new Index(
            $this->authenticatorServiceMock,
            $this->redirectFactoryMock,
            $this->requestMock,
            $this->managerMock,
            $this->loggerMock,
            $this->authMock,
        );
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        $this->authenticatorServiceMock->expects($this->once())->method('authenticate');
        $this->assertSame($this->redirectMock, $this->index->execute());
    }

    /**
     * @dataProvider exceptionDataProvider
     * @param Throwable $throwable
     * @param int $message
     * @param int $error
     * @return void
     */
    public function testExecuteAuthenticateError(Throwable $throwable, int $message, int $error): void
    {
        $this->authenticatorServiceMock->expects($this->once())
            ->method('authenticate')
            ->willThrowException($throwable);
        $this->loggerMock->expects($this->exactly($error))->method('error');
        $this->managerMock->expects($this->exactly($message))->method('addErrorMessage');
        $this->assertSame($this->redirectMock, $this->index->execute());
    }

    /**
     * @return array[]
     */
    public function exceptionDataProvider(): array
    {
        return [
            [new AuthenticationException(), 1, 0],
            [new Exception(), 1, 1],
        ];
    }
}
