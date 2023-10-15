<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Model;

use Awesoft\GoogleSignIn\Model\User;
use Google\Service\Oauth2\Userinfo;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @return void
     */
    public function testWithUserinfoData(): void
    {
        $userinfo = $this->createMock(Userinfo::class);
        $userinfo->expects($this->exactly(2))->method('getEmail')->willReturn('user@example.com');
        $userinfo->expects($this->once())->method('getFamilyName')->willReturn('Family Name');
        $userinfo->expects($this->once())->method('getGivenName')->willReturn('Given Name');

        $objectManagerHelper = new ObjectManager($this);
        $user = $objectManagerHelper->getObject(User::class);
        $user->withUserinfoData($userinfo, 'password', 1);
    }
}
