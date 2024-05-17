<?php

namespace Awesoft\GoogleSignIn\Api\Block\Adminhtml\Button;

interface SignInInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @return string
     */
    public function getLoginUrl(): string;

    /**
     * @return string
     */
    public function getImageUrl(): string;
}
