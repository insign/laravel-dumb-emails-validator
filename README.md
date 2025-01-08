# Laravel Dumb Emails Validator

This package provides a custom validator for Laravel that checks for common typos in email domains.

## Installation

1.  Install the package via Composer:

    ```bash
    composer require insign/laravel-dumb-emails-validator
    ```
2.  Publish the config file (optional):

    ```bash
    php artisan vendor:publish --provider="insign\DumbEmailsValidator\DumbEmailsValidatorServiceProvider" --tag="config"
    ```

## Usage

Use the `dumb_email` rule in your validation rules:

```php
use Illuminate\Support\Facades\Validator;

$validator = Validator::make($request->all(), [
    'email' => 'required|email|dumb_email',
]);

if ($validator->fails()) {
    // Handle validation errors
    dd($validator->errors());
}
```

## Customization

You can customize the list of domain corrections and the error message in the `config/dumb-emails.php` file.

```php
return [
    'corrections' => [
        'gmal.com' => 'gmail.com',  // Add your custom corrections here
        // ...
    ],

    'message' => "Did you mean @:correct_domain?" // Change the default message, you can use :attribute and :correct_domain placeholders
];
```

The `:attribute` placeholder will be replaced with the field name (e.g., "email"), and `:correct_domain` will be replaced with the suggested correct domain.
```

**6. `LICENSE`**

```
            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
                    Version 2, December 2004

Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

Everyone is permitted to copy and distribute verbatim or modified
copies of this license document, and changing it is allowed as long
as the name is changed.

            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

0. You just DO WHAT THE FUCK YOU WANT TO.
```

**How to Use in Your Laravel Project**

1.  **Create the extension directory:**  In your Laravel project's root, create a directory like `packages/your-vendor/laravel-dumb-emails-validator` (replace `your-vendor` with your vendor name, e.g., your GitHub username).
2.  **Place the files:** Put the files from above (`src/`, `config/`, `README.md`, `LICENSE`) into the `laravel-dumb-emails-validator` directory.
3.  **Composer:** In your project's main `composer.json`, add the following to the `autoload` section (under `psr-4`):

    ```json
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            // ... other autoload entries
            "insign\\DumbEmailsValidator\\": "packages/your-vendor/laravel-dumb-emails-validator/src"
        }
    },
    ```
    And add in the `repositories` section:

    ```json
    "repositories": [
        {
            "type": "path",
            "url": "packages/your-vendor/laravel-dumb-emails-validator"
        }
    ]
    ```

4.  **Install:** Run `composer require your-vendor/laravel-dumb-emails-validator`
5.  **Publish Config:** Run `php artisan vendor:publish --provider="insign\DumbEmailsValidator\DumbEmailsValidatorServiceProvider" --tag="config"`
6.  **Register Service Provider:** Add the provider to your `config/app.php`:

    ```php
    'providers' => [
        // ...
        insign\DumbEmailsValidator\DumbEmailsValidatorServiceProvider::class,
    ],
    ```

Now you can use the `dumb_email` validation rule as shown in the `README.md`. Remember to adjust the vendor name and namespaces as needed if you change them.