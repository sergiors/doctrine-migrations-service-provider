Doctrine Migrations Service Provider
------------------------------------
[![Build Status](https://travis-ci.org/sergiors/doctrine-migrations-service-provider.svg?branch=master)](https://travis-ci.org/sergiors/doctrine-migrations-service-provider)

Install
-------
```bash
composer require sergiors/doctrine-migrations-service-provider
```

```php
use Silex\Provider\DoctrineServiceProvider;
use Sergiors\Pimple\Provider\DoctrineMigrationsServiceProvider;

$container = new \Pimple\Container();

$container['console'] = function () {
    return new \Symfony\Component\Console\Application();
};
$container->register(new DoctrineServiceProvider());
$container->register(new DoctrineMigrationsServiceProvider());

$container['console']->run();
```

License
-------
MIT
