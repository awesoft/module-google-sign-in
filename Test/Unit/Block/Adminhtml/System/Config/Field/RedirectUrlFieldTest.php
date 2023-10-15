<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Block\Adminhtml\System\Config\Field;

use Awesoft\GoogleSignIn\Block\Adminhtml\System\Config\Field\RedirectUrlField;
use Awesoft\GoogleSignIn\Model\Config;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use PHPUnit\Framework\TestCase;

class RedirectUrlFieldTest extends TestCase
{
    public function testRender(): void
    {
        $url = 'https://example.com/';
        $configMock = $this->createMock(Config::class);
        $configMock->expects($this->once())->method('getRedirectUrl')->willReturn($url);

        $redirectUrlField = new RedirectUrlField($configMock, $this->createMock(Context::class));

        $this->assertStringContainsString(
            '<a target="_blank" href="' . $url . '">' . $url . '</a>',
            $redirectUrlField->render($this->createMock(AbstractElement::class))
        );
    }
}
