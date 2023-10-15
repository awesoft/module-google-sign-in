<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Model\Config\Source;

use Awesoft\GoogleSignIn\Model\Config\Source\Role;
use Magento\Authorization\Model\ResourceModel\Role\CollectionFactory;
use Magento\Authorization\Model\ResourceModel\Role\Collection;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    /**
     * @return void
     */
    public function testToOptionArray(): void
    {
        $options = ['label' => 'value'];
        $collectionMock = $this->createMock(Collection::class);
        $collectionMock->expects($this->once())->method('setRolesFilter')->willReturnSelf();
        $collectionMock->expects($this->once())->method('toOptionArray')->willReturn($options);

        $collectionFactoryMock = $this->createMock(CollectionFactory::class);
        $collectionFactoryMock->expects($this->once())->method('create')->willReturn($collectionMock);

        $this->assertSame($options, (new Role($collectionFactoryMock))->toOptionArray());
    }
}
