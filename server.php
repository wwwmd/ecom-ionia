```php
<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Serve existing public files directly, including storage images.
$publicPath = __DIR__ . '/public';
$filePath = $publicPath . $uri;

if ($uri !== '/' && is_file($filePath)) {
    return false;
}

// Serve Laravel application requests.
require_once $publicPath . '/index.php';
```
