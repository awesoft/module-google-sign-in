<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Model\ResourceModel;

use Awesoft\GoogleSignIn\Model\ResourceModel\User as UserResourceModel;
use Awesoft\GoogleSignIn\Model\User;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyExistsException
     */
    public function testSaveUser(): void
    {
        $contextMock = $this->createMock(Context::class);
        $adapterMock = $this->createMock(AdapterInterface::class);
        $resourceMock = $this->createMock(ResourceConnection::class);

        $adapterMock->expects($this->once())->method('update');
        $adapterMock->expects($this->once())->method('commit');
        $adapterMock->expects($this->once())->method('beginTransaction');
        $resourceMock->expects($this->atLeastOnce())->method('getConnection')->willReturn($adapterMock);
        $contextMock->expects($this->atLeastOnce())->method('getResources')->willReturn($resourceMock);

        $objectManagerHelper = new ObjectManager($this);
        $userResourceModel = $objectManagerHelper->getObject(UserResourceModel::class, ['context' => $contextMock]);
        $userResourceModel->saveUser($this->createMock(User::class));
    }
}
