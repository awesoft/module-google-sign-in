# Magento 2 - Admin Google Sign In

## Description
Allows you to configure and use Google OAuth login to your Magento 2 Admin page.

## Features
- Configure Google OAuth credentials.
- Disable default login form.
- Limit accounts to specific hosted domain(s).
- Allow automatically create an Admin user.
- Specify specific user role on newly created users.

## Installation
- Via composer (Recommended)
  ```shell
  composer require awesoft/module-google-sign-in
  ```
- Manual installation
  - Clone or download the latest release: https://github.com/awesoft/module-google-sign-in/releases/latest/
  - Extract all files to `app/code/Awesoft/GoogleSignIn/`
  - Run `setup:upgrade` command
    ```shell
    php bin/magento setup:upgrade
    ```
