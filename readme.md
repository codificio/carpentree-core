# Carpentree Core

## Requirements
- PHP 7.2+
- Laravel 5.7+
- MySQL 5.7+ (this package use `json` column)
- `exif` extension (on most systems it will be installed by default).
- [GD](http://php.net/manual/en/book.image.php) extension
- If you want to create PDF or SVG thumbnails [Imagick](http://php.net/manual/en/book.imagick.php) and [Ghostscript](https://www.ghostscript.com/) are required. 
- For the creation of thumbnails of video files `ffmpeg` should be installed on your system.

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
- [Optional] If you want, remove default `User` model class in `App/User`.
- [Optional] If you want, remove no more useful Middleware and Controller. Remember this package aim to provide a _stateless_ application skeleton, so default `session` authentication will not be used.
- In your `config/auth.php` configuration file, you should set the `model` option of the `users` provider to the new User class.
``` php
'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \Carpentree\Core\Models\User::class,
        ],
    ],
```

- Remove default Laravel routes from `routes` folder unless you need to override it. By default, Carpentree has a root route (and a view) for the index in order to initialize React app.
> Do not remove files because they will be useful to make your own application. Just remove its content before starting developing.

## Usage

### Authorization and authentication

#### CORS

CORS Middleware is base on https://github.com/barryvdh/laravel-cors.

CORS middleware is already included in `api` routes group. 

In order to make your own configuration, publish CORS config file:

``` bash
$ php artisan vendor:publish --provider="Barryvdh\Cors\ServiceProvider" 
```

> Note: When using custom headers, like X-Auth-Token or X-Requested-With, you must set the allowedHeaders to include those headers. You can also set it to array('*') to allow all custom headers.

> Note: If you are explicitly whitelisting headers, you must include Origin or requests will fail to be recognized as CORS.

``` php
return [
     /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['Content-Type', 'X-Requested-With'],
    'allowedMethods' => ['*'], // ex: ['GET', 'POST', 'PUT',  'DELETE']
    'exposedHeaders' => [],
    'maxAge' => 0,
];
```

> Note: Because of http method overriding in Laravel, allowing POST methods will also enable the API users to perform PUT and DELETE requests as well.

#### Enable social authentication

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

#### Roles and permissions

There is a `Super Admin` role who can do everything. To make sure you correctly pass the check on this role, you must use the native Laravel `@can` and `can()` directives.

It is generally best to code the app around `permissions` only. That way you can always use the native Laravel `@can` and `can()` directives everywhere in your app.

##### Middleware

Since this package base his roles and permissions system on `spatie\laravel-permission`, if you want to use middlewares, you need to add them inside your `app/Http/Kernel.php` file.

``` php
protected $routeMiddleware = [
    // ...
    'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
];
```

##### Manage permissions

- Publish config file:

``` bash
$ php artisan vendor:publish --provider="Carpentree\Core\CoreServiceProvider --tag=config" 
```

- Add permissions you want to the `carpentree.permissions` config file, e.g:

``` php
'permissions' => [
    'users' => [
        'create',
        'read',
        'update',
        'delete',
        'manage-permissions'
    ],
    
    'group-key' => [
        'permission-key-1',
        'permission-key-2',
        // ...
    ]
]
```

- Update your database by running the console command:

``` bash
$ php artisan carpentree:refresh-permissions
```

Permission final name will be `group-key.permission-key` adn you can refer to it from the code, for example, in this way:

``` php
$user->can('users.delete');
```

### Assign statuses to Eloquent models

We use `laravel-model-status` package by Spatie. Look at their [documentation](https://github.com/spatie/laravel-model-status).

### Assign categories to Eloquent models

To add categories support to your eloquent models simply use `\Carpentree\Core\Traits\Categorizable` trait.

For his feature, we were inspired by https://github.com/rinvex/laravel-categories. Read it documentation for more info.

### Full text search

TODO (Algolia)

### Meta fields

Assign `HasMeta` trait to a model in order to enable meta fields for a model.
In order to maintain both flexibility and simplicity, you can consider `value` attributes of the meta fields as JSON container.

### Localization

Publish configuration with this command:

``` bash
php artisan vendor:publish --tag=translatable
``` 

After that, available locales are set in configuration file `config\translatable.php`.

## Security

If you discover any security related issues, please email enrico@codificio.com instead of using the issue tracker.

## License

MIT. Please see the [license file](license.md) for more information.
