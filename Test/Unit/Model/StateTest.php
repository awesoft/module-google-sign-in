<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Model;

use Awesoft\GoogleSignIn\Exception\SecurityAuthenticationException;
use Awesoft\GoogleSignIn\Model\State;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class StateTest extends TestCase
{
    private LoggerInterface|MockObject $loggerMock;
    private Session|MockObject $sessionMock;
    private Random|MockObject $randomMock;
    private State $state;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->randomMock = $this->createMock(Random::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->addMethods(['unsData', 'setData'])
            ->onlyMethods(['getData'])
            ->getMock();
        $this->state = new State($this->loggerMock, $this->sessionMock, $this->randomMock);
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function testGenerate(): void
    {
        $value = 'generated-value';
        $this->sessionMock->expects($this->once())->method('setData')->with(State::KEY, $value);
        $this->randomMock->expects($this->once())->method('getRandomString')->with(State::LENGTH)->willReturn($value);
        $this->assertSame($value, $this->state->generate());
    }

    /**
     * @return void
     */
    public function testGetData(): void
    {
        $value = 'state-data';
        $this->sessionMock->expects($this->once())->method('unsData')->with(State::KEY);
        $this->sessionMock->expects($this->once())->method('getData')->with(State::KEY)->willReturn($value);
        $this->assertSame($value, $this->state->getData());
    }

    /**
     * @return void
     * @throws SecurityAuthenticationException
     */
    public function testValidate(): void
    {
        $value = 'valid-state-data';
        $this->sessionMock->expects($this->once())->method('getData')->with(State::KEY)->willReturn($value);
        $this->sessionMock->expects($this->once())->method('unsData')->with(State::KEY);
        $this->state->validate($value);
    }

    /**
     * @return void
     * @throws SecurityAuthenticationException
     */
    public function testValidateInvalidState(): void
    {
        $this->sessionMock->expects($this->once())->method('getData')->with(State::KEY)->willReturn('state-data');
        $this->sessionMock->expects($this->once())->method('unsData')->with(State::KEY);
        $this->loggerMock->expects($this->once())->method('error');

        $this->expectException(SecurityAuthenticationException::class);
        $this->state->validate('invalid-state-data');
    }
}
