<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Controller\Adminhtml\Index;

use Awesoft\GoogleSignIn\Api\Service\AuthenticatorInterface;
use Magento\Backend\Model\Auth;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;

class Index implements HttpGetActionInterface
{
    /**
     * Index controller constructor.
     *
     * @param AuthenticatorInterface $authenticator
     * @param RedirectFactory $redirectFactory
     * @param Auth $auth
     */
    public function __construct(
        private readonly AuthenticatorInterface $authenticator,
        private readonly RedirectFactory $redirectFactory,
        private readonly Auth $auth,
    ) {
    }

    /**
     * Execute index page
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        if ($this->auth->isLoggedIn()) {
            return $this->redirectFactory
                ->create()
                ->setPath('adminhtml/index');
        }

        return $this->redirectFactory
            ->create()
            ->setPath($this->authenticator->getAuthUrl());
    }
}
