<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Exception;

use Awesoft\GoogleSignIn\Exception\SecurityAuthenticationException;
use PHPUnit\Framework\TestCase;

class SecurityAuthenticationExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertSame(
            'Invalid security or form key. Please refresh the page.',
            (new SecurityAuthenticationException())->getMessage()
        );
    }
}
