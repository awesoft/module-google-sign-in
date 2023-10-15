<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Plugin\Model;

use Awesoft\GoogleSignIn\Plugin\Model\TfaPlugin;
use Magento\TwoFactorAuth\Model\Tfa;
use PHPUnit\Framework\TestCase;

class TfaPluginTest extends TestCase
{
    /**
     * @return void
     */
    public function testAfterGetAllowedUrls(): void
    {
        $urls = ['adminhtml_auth_index'];

        $this->assertSame(
            array_merge($urls, TfaPlugin::ALLOWED_URLS),
            (new TfaPlugin())->afterGetAllowedUrls($this->createMock(Tfa::class), $urls)
        );
    }
}
