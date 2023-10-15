<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Exception;

use Awesoft\GoogleSignIn\Exception\PermissionAuthenticationException;
use PHPUnit\Framework\TestCase;

class PermissionAuthenticationExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertSame(
            'More permissions are needed to access this.',
            (new PermissionAuthenticationException())->getMessage()
        );
    }
}
