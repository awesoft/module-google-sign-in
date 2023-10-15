<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Test\Unit\Model\ResourceModel\User;

use Awesoft\GoogleSignIn\Model\ResourceModel\User\Collection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CollectionTest extends TestCase
{
    /** @var Collection $collection */
    private Collection $collection;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $selectMock = $this->createMock(Select::class);
        $abstractDbMock = $this->createMock(AbstractDb::class);
        $adapterMock = $this->createMock(AdapterInterface::class);

        $selectMock->expects($this->once())->method('limit');
        $selectMock->expects($this->exactly(2))->method('where');
        $selectMock->expects($this->any())->method('joinLeft')->willReturnSelf();
        $adapterMock->expects($this->atLeastOnce())->method('select')->willReturn($selectMock);
        $abstractDbMock->expects($this->once())->method('getConnection')->willReturn($adapterMock);

        $this->collection = new Collection(
            $this->createMock(EntityFactoryInterface::class),
            $this->createMock(LoggerInterface::class),
            $this->createMock(FetchStrategyInterface::class),
            $this->createMock(ManagerInterface::class),
            $adapterMock,
            $abstractDbMock,
        );
    }

    /**
     * @return void
     */
    public function testLoadByUsername(): void
    {
        $this->collection->loadByUsername('username');
    }

    /**
     * @return void
     */
    public function testLoadByEmail(): void
    {
        $this->collection->loadByEmail('user@example.com');
    }

    /**
     * @return void
     */
    public function testLoadById(): void
    {
        $this->collection->loadById(1);
    }
}
