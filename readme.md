# Core

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

- Via Composer
``` bash
$ composer require carpentree/core
```
- Run migrations
``` bash
$ php artisan migrate
```
- Since the package uses Laravel Passport, you need to install it
``` bash
$ php artisan passport:install
```
- Remove default user migrations from your Laravel installation.
- Remove default User model class.

## Usage

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email enrico@codificio.com instead of using the issue tracker.

## Credits

- [Enrico Nardo][link-author]
- [All Contributors][link-contributors]

## License

license MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/carpentree/core.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/carpentree/core.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/carpentree/core/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/carpentree/core
[link-downloads]: https://packagist.org/packages/carpentree/core
[link-travis]: https://travis-ci.org/carpentree/core
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/carpentree
[link-contributors]: ../../contributors
