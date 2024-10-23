
```php
<?php

use Doctrine\DBAL\DriverManager;
use Gingdev\TursoHranaDBAL\Driver;

require __DIR__. '/vendor/autoload.php';

$connectionParams = [
    'password' => $authToken,
    'host' => $yourAppUrl,
    'driverClass' => Driver::class,
];
$conn = DriverManager::getConnection($connectionParams);

$result = $conn->executeQuery('SELECT * FROM users');

print_r($result->fetchAllNumeric());
```
