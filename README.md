# IdeoLearn Core Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ideolean/core.svg?style=flat-square)](https://packagist.org/packages/ideolean/core)
[![Total Downloads](https://img.shields.io/packagist/dt/ideolean/core.svg?style=flat-square)](https://packagist.org/packages/ideolean/core)
[![License](https://img.shields.io/packagist/l/ideolean/core.svg?style=flat-square)](https://packagist.org/packages/ideolean/core)
[![PHP Version](https://img.shields.io/packagist/php-v/ideolean/core.svg?style=flat-square)](https://packagist.org/packages/ideolean/core)

## 🚀 Quick Installation

```bash
composer require ideolean/core
```

> **Note**: This package will automatically:
>
> - Install required dependencies
> - Publish configuration files
> - Register service providers

## 📦 Package Dependencies

- [mcamara/laravel-localization](https://github.com/mcamara/laravel-localization)
- [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable)
- [league/flysystem-aws-s3-v3](https://github.com/thephpleague/flysystem-aws-s3-v3)

### 3. Environment Setup

Add to your `.env`:

```env
# AWS Configuration
AWS_ACCESS_KEY_ID=your-key-id
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=your-region
AWS_BUCKET=your-bucket

# Localization Configuration
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

## 🛠️ Available Commands

| Command | Description |
|---------|-------------|
| `php artisan ideolearn:publish` | Publish all configurations |

## 🔍 VSCode Extensions

For better development experience, we recommend these VSCode extensions:

- [Laravel Extension Pack](https://marketplace.visualstudio.com/items?itemName=onecentlin.laravel-extension-pack)
- [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
- [Laravel Blade Snippets](https://marketplace.visualstudio.com/items?itemName=onecentlin.laravel-blade)
- [Laravel Blade Formatter](https://marketplace.visualstudio.com/items?itemName=shufo.vscode-blade-formatter)
- [DotENV](https://marketplace.visualstudio.com/items?itemName=mikestead.dotenv)

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
