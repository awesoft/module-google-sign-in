<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Controller\Adminhtml\Verify;

use Awesoft\GoogleSignIn\Api\Service\AuthenticatorInterface;
use Awesoft\GoogleSignIn\Exception\AuthenticationException;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class Index implements HttpGetActionInterface
{
    /**
     * Verify controller constructor.
     *
     * @param AuthenticatorInterface $authenticator
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     * @param ManagerInterface $manager
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly AuthenticatorInterface $authenticator,
        private readonly RedirectFactory $redirectFactory,
        private readonly RequestInterface $request,
        private readonly ManagerInterface $manager,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Execute verification and authenticate
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $redirect = $this->redirectFactory
            ->create()
            ->setHeader('Pragma', 'no-cache', true)
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0', true);

        try {
            $this->authenticator->authenticate(
                (string)$this->request->getParam('code'),
                (string)$this->request->getParam('state'),
            );

            return $redirect->setPath('adminhtml/index');
        } catch (AuthenticationException $authenticationException) {
            $this->manager->addErrorMessage($authenticationException->getMessage());
        } catch (Throwable $throwable) {
            $this->logger->error($throwable->getMessage(), $throwable->getTrace());
            $this->manager->addErrorMessage(__('An authentication error occurred. Verify and try again.'));
        }

        return $redirect->setPath('adminhtml/index');
    }
}
