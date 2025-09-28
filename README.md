# A wrapper for Filament Component like FilamentDateTimePicker,TextColumn,TextEntry to support Nepali Date.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rohanadhikari/filament-nepali-datetime.svg?style=flat-square)](https://packagist.org/packages/rohanadhikari/filament-nepali-datetime)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/rohanadhikari/filament-nepali-datetime/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/rohanadhikari/filament-nepali-datetime/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/rohanadhikari/filament-nepali-datetime/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/rohanadhikari/filament-nepali-datetime/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/rohanadhikari/filament-nepali-datetime.svg?style=flat-square)](https://packagist.org/packages/rohanadhikari/filament-nepali-datetime)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require rohanadhikari/filament-nepali-datetime
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-nepali-datetime-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-nepali-datetime-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-nepali-datetime-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentNepaliDatetime = new RohanAdhikari\FilamentNepaliDatetime();
echo $filamentNepaliDatetime->echoPhrase('Hello, RohanAdhikari!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rohan Adhikari](https://github.com/rohanAdhikari1)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
