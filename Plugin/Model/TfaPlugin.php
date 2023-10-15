<?php

declare(strict_types=1);

namespace Awesoft\GoogleSignIn\Plugin\Model;

use Magento\TwoFactorAuth\Model\Tfa;

class TfaPlugin
{
    public const ALLOWED_URLS = [
        'awesoft_google_signin_index_index',
        'awesoft_google_signin_verify_index',
    ];

    /**
     * Add module controller reference to allowed URLs
     *
     * @param Tfa $tfa
     * @param array $allowedUrls
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAllowedUrls(Tfa $tfa, array $allowedUrls): array
    {
        return array_merge($allowedUrls, self::ALLOWED_URLS);
    }
}
