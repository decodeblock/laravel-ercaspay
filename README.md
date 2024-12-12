# Laravel Ercaspay

A Laravel package for seamless integration with the Ercaspay payment gateway API.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/decodeblock/laravel-ercaspay.svg?style=flat-square)](https://packagist.org/packages/decodeblock/laravel-ercaspay)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/decodeblock/laravel-ercaspay/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/decodeblock/laravel-ercaspay/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/decodeblock/laravel-ercaspay/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/decodeblock/laravel-ercaspay/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/decodeblock/laravel-ercaspay.svg?style=flat-square)](https://packagist.org/packages/decodeblock/laravel-ercaspay)

## Features

- Easy integration with Ercaspay payment gateway
- Support for multiple payment methods
- Webhook handling
- Payment verification
- Comprehensive error handling

## Requirements

- PHP 8.1 or higher
- Laravel 9.0 or higher

## Installation

Install the package via composer:

```bash
composer require decodeblock/laravel-ercaspay
```
## Configuration

1. Publish the config file:

    ```bash
    php artisan vendor:publish --tag="laravel-ercaspay-config"
    ```

2. Add your Ercaspay credentials to your .env file:
   
    ```
    ERCASPAY_PUBLIC_KEY=your_public_key
    ERCASPAY_SECRET_KEY=your_secret_key
    ```
