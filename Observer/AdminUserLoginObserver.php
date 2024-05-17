<?php

namespace Awesoft\GoogleSignIn\Observer;

use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\Plugin\AuthenticationException;

class AdminUserLoginObserver implements ObserverInterface
{
    /**
     * @param ConfigInterface $config
     */
    public function __construct(private readonly ConfigInterface $config)
    {
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws AuthenticationException
     */
    public function execute(Observer $observer): void
    {
        if ($this->config->isDisableLoginForm()) {
            throw new AuthenticationException(
                __(
                    'The account sign-in was incorrect or your account is disabled temporarily. '
                    . 'Please wait and try again later.'
                )
            );
        }
    }
}
