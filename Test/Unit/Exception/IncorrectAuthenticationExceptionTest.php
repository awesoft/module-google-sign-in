<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Exception;

use Awesoft\GoogleSignIn\Exception\IncorrectAuthenticationException;
use PHPUnit\Framework\TestCase;

class IncorrectAuthenticationExceptionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstruct(): void
    {
        $this->assertSame(
            'The account sign-in was incorrect or your account is disabled temporarily.'
            . ' Please wait and try again later.',
            (new IncorrectAuthenticationException())->getMessage()
        );
    }
}
