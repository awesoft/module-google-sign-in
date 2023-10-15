<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Block\Adminhtml\Button;

use Awesoft\GoogleSignIn\Model\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class SignIn extends Template
{
    /**
     * SignIn constructor
     *
     * @param Config $config
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        private readonly Config $config,
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
