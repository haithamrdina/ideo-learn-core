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
- [aws/aws-sdk-php](https://github.com/aws/aws-sdk-php)

## ⚙️ Configuration

### 1. Add Middlewares

Add to `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middlewares
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
    ],
];
```

### 2. Publish Configurations

```bash
php artisan ideolean:publish
```

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

## 💡 Usage Examples

### Localization Routes

```php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect']
], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
```

### Translatable Model

```php
use Spatie\Translatable\HasTranslations;

class Course extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description'];
    
    protected $fillable = ['title', 'description'];
}

// Usage
$course = Course::create([
    'title' => [
        'en' => 'English Title',
        'fr' => 'French Title'
    ]
]);
```

## 📝 Configuration Files

### Localization (`config/laravellocalization.php`)

```php
return [
    'supportedLocales' => [
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
        'fr' => ['name' => 'French', 'script' => 'Latn', 'native' => 'français'],
    ],
];
```

### Translatable (`config/translatable.php`)

```php
return [
    'fallback_locale' => 'en',
    'required_locales' => ['en', 'fr'],
];
```

## 🛠️ Available Commands

| Command | Description |
|---------|-------------|
| `php artisan ideolean:publish` | Publish all configurations |

## 🔍 VSCode Extensions

For better development experience, we recommend these VSCode extensions:

- [Laravel Extension Pack](https://marketplace.visualstudio.com/items?itemName=onecentlin.laravel-extension-pack)
- [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
- [Laravel Blade Snippets](https://marketplace.visualstudio.com/items?itemName=onecentlin.laravel-blade)
- [Laravel Blade Formatter](https://marketplace.visualstudio.com/items?itemName=shufo.vscode-blade-formatter)
- [DotENV](https://marketplace.visualstudio.com/items?itemName=mikestead.dotenv)

## 🐛 Debugging

For debugging in VSCode:

1. Install [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug)
2. Add this to your `launch.json`:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003
        }
    ]
}
```

## 📋 Todo List

- [ ] Add unit tests
- [ ] Add API documentation
- [ ] Add examples for AWS integration
- [ ] Add caching layer

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
