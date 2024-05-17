<?php

namespace Awesoft\GoogleSignIn\Api\Model;

interface StateInterface
{
    /** @const int LENGTH */
    public const LENGTH = 30;

    /** @const string KEY */
    public const KEY = 'awesoft.google-signin.state';

    /**
     * @return string
     */
    public function generate(): string;

    /**
     * @return string
     */
    public function getData(): string;

    /**
     * @param string $state
     * @return void
     */
    public function validate(string $state): void;
}
