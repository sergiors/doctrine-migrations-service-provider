Doctrine Migrations Service Provider
------------------------------------
[![Build Status](https://travis-ci.org/sergiors/doctrine-migrations-service-provider.svg?branch=master)](https://travis-ci.org/sergiors/doctrine-migrations-service-provider)

Install
-------
```bash
composer require sergiors/doctrine-migrations-service-provider "dev-master"
```

```php
use Silex\Provider\DoctrineServiceProvider;
use Sergiors\Silex\Provider\ConsoleServiceProvider;
use Sergiors\Silex\Provider\DoctrineMigrationsServiceProvider;

$app->register(new ConsoleServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new DoctrineMigrationsServiceProvider());
```

License
-------
MIT
