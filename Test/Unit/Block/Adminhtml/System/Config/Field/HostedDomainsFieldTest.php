<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Block\Adminhtml\System\Config\Field;

use Awesoft\GoogleSignIn\Block\Adminhtml\System\Config\Field\HostedDomainsField;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\State;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\View\Element\Template\File\Resolver;
use Magento\Framework\View\Element\Template\File\Validator;
use Magento\Framework\View\TemplateEngineInterface;
use Magento\Framework\View\TemplateEnginePool;
use PHPUnit\Framework\TestCase;

class HostedDomainsFieldTest extends TestCase
{
    public function testRender(): void
    {
        $readMock = $this->createMock(ReadInterface::class);
        $fsMock = $this->createMock(Filesystem::class);
        $stateMock = $this->createMock(State::class);
        $contextMock = $this->createMock(Context::class);
        $resolverMock = $this->createMock(Resolver::class);
        $validatorMock = $this->createMock(Validator::class);
        $elementMock = $this->createMock(AbstractElement::class);
        $eventManagerMock = $this->createMock(ManagerInterface::class);
        $engineMock = $this->createMock(TemplateEngineInterface::class);
        $enginePoolMock = $this->createMock(TemplateEnginePool::class);

        $eventManagerMock->expects($this->once())->method('dispatch');
        $validatorMock->expects($this->once())->method('isValid')->willReturn(true);
        $readMock->expects($this->once())->method('getRelativePath')->willReturn('/');
        $engineMock->expects($this->once())->method('render')->willReturn('domain.com');
        $enginePoolMock->expects($this->once())->method('get')->willReturn($engineMock);
        $stateMock->expects($this->once())->method('getAreaCode')->willReturn('adminhtml');
        $fsMock->expects($this->once())->method('getDirectoryRead')->willReturn($readMock);
        $resolverMock->expects($this->once())->method('getTemplateFileName')->willReturn('file.phtml');

        $contextMock->expects($this->once())->method('getFilesystem')->willReturn($fsMock);
        $contextMock->expects($this->once())->method('getAppState')->willReturn($stateMock);
        $contextMock->expects($this->once())->method('getResolver')->willReturn($resolverMock);
        $contextMock->expects($this->once())->method('getValidator')->willReturn($validatorMock);
        $contextMock->expects($this->once())->method('getEnginePool')->willReturn($enginePoolMock);
        $contextMock->expects($this->once())->method('getEventManager')->willReturn($eventManagerMock);

        $this->assertStringContainsString('domain.com', (new HostedDomainsField($contextMock))->render($elementMock));
    }
}
