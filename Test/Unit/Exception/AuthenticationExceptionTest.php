<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Exception;

use Awesoft\GoogleSignIn\Exception\AuthenticationException;
use Magento\Framework\Phrase;
use PHPUnit\Framework\TestCase;

class AuthenticationExceptionTest extends TestCase
{
    /**
     * @dataProvider constructDataProvider
     * @param string $expected
     * @param Phrase|null $phrase
     * @return void
     */
    public function testConstruct(string $expected, ?Phrase $phrase): void
    {
        $this->assertSame($expected, (new AuthenticationException($phrase))->getMessage());
    }

    /**
     * @return array
     */
    public function constructDataProvider(): array
    {
        return [
            ['An authentication error occurred. Verify and try again.', null],
            ['error message', __('error message')],
        ];
    }
}
