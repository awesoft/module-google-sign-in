<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Model\Config\Source;

use Magento\Authorization\Model\ResourceModel\Role\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class Role implements OptionSourceInterface
{
    /**
     * Role constructor.
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
    ) {
    }

    /**
     * Get roles options array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return $this->collectionFactory->create()->setRolesFilter()->toOptionArray();
    }
}
