# Laravel Doc Generator

This package generates documentation for Laravel controllers based on specially formatted comments.

## Installation

```bash
composer require raju/laravel-doc-generator
```

## Usage

Run the following Artisan command to generate documentation:

```bash
php artisan doc:generate
```

The generated documentation will be saved as `storage/docs.json`.

## Markup Format

Add comments like this in your controllers:

```php
/* raju
Description: Fetches user data by ID.
Where Used: User profile API.
*/
public function getUserData($id)
{
    // Your logic here
}
```
