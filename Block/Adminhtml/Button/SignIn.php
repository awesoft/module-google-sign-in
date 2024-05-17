<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Block\Adminhtml\Button;

use Awesoft\GoogleSignIn\Api\Block\Adminhtml\Button\SignInInterface;
use Awesoft\GoogleSignIn\Api\Model\ConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class SignIn extends Template implements SignInInterface
{
    /**
     * SignIn constructor
     *
     * @param ConfigInterface $config
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        private readonly ConfigInterface $config,
        Context $context,
        array $data = [],
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Is sign in button enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    /**
     * Get Google login URL
     *
     * @return string
     */
    public function getLoginUrl(): string
    {
        return $this->config->getLoginUrl();
    }

    /**
     * Get Sign in with Google button image
     *
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->getViewFileUrl('Awesoft_GoogleSignIn::images/btn-google-signin.svg');
    }
}
