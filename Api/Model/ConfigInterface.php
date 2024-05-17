<?php

namespace Awesoft\GoogleSignIn\Api\Model;

interface ConfigInterface
{
    /** @const string XML_PATH_IS_ENABLED */
    public const XML_PATH_IS_ENABLED = 'awesoft/google_signin/is_enabled';

    /** @const string XML_PATH_CLIENT_ID */
    public const XML_PATH_CLIENT_ID = 'awesoft/google_signin/client_id';

    /** @const string XML_PATH_CLIENT_SECRET */
    public const XML_PATH_CLIENT_SECRET = 'awesoft/google_signin/client_secret';

    /** @const string XML_PATH_HOSTED_DOMAINS */
    public const XML_PATH_HOSTED_DOMAINS = 'awesoft/google_signin/hosted_domains';

    /** @const string XML_PATH_ENABLE_USER_CREATE */
    public const XML_PATH_ENABLE_USER_CREATE = 'awesoft/google_signin/enable_user_create';

    /** @const string XML_PATH_DISABLE_LOGIN_FORM */
    public const XML_PATH_DISABLE_LOGIN_FORM = 'awesoft/google_signin/disable_login_form';

    /** @const string XML_PATH_ROLE_ID */
    public const XML_PATH_ROLE_ID = 'awesoft/google_signin/role_id';

    /** @const string URL_PATH_LOGIN */
    public const URL_PATH_LOGIN = 'awesoft_google_signin/index';

    /** @const string URL_PATH_REDIRECT */
    public const URL_PATH_REDIRECT = 'awesoft_google_signin/verify';

    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @return string
     */
    public function getClientId(): string;

    /**
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * @return array
     */
    public function getHostedDomains(): array;

    /**
     * @return string
     */
    public function getRedirectUrl(): string;

    /**
     * @return string
     */
    public function getLoginUrl(): string;

    /**
     * @return bool
     */
    public function isUserCreateEnabled(): bool;

    /**
     * @return int
     */
    public function getRoleId(): int;

    /**
     * @return bool
     */
    public function isDisableLoginForm(): bool;
}
