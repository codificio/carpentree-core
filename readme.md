# Core

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

- Via Composer
``` bash
$ composer require carpentree/core
```
- **IMPORTANT!** Remove default Laravel migrations about users:
    - `yyyy_mm_dd_HHiiss_create_users_table.php`
    - `yyyy_mm_dd_HHiiss_create_password_resets_table.php`

- Run migrations
``` bash
$ php artisan migrate
```
- Since the package uses Laravel Passport, you need to install it
``` bash
$ php artisan passport:install
```
- In your `config/auth.php` configuration file, you should set the `driver` option of the `api` authentication guard to `passport`.
``` php
'guards' => [
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```
- Remove default `User` model class in `App/User`.
- In your `config/auth.php` configuration file, you should set the `model` option of the `users` provider to the new User class.
``` php
'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \Carpentree\Core\Models\User::class,
        ],
    ],
```

## Usage

### Enable social authentication

Add credentials for the OAuth services your application utilizes. These credentials should be placed in your `config/services.php` configuration file, and should use the key equals to provider name (e.g., `facebook`, `google`, `github` etc.).

For example:

``` php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URL'),
],
```

> We will use Socialite just for retrieving user details from an access token so we can fill client_id, client_secret, redirect with **empty strings (not NULL)** because they won’t be used in our flow.

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

MIT. Please see the [license file](license.md) for more information.

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
