<?php

namespace Sergiors\Pimple\Tests\Provider;

use Pimple\Container;
use Silex\Provider\DoctrineServiceProvider;
use Sergiors\Pimple\Provider\DoctrineMigrationsServiceProvider;

class DoctrineMigrationsServiceProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldReturnTheCommands()
    {
        $container = new Container();
        $container['console'] = function () {
            return new \Symfony\Component\Console\Application();
        };
        $container->register(new DoctrineServiceProvider());
        $container->register(new DoctrineMigrationsServiceProvider(), [
            'migrations.options' => [
                'name' => 'Doctrine Migrations',
                'namespace' => 'DoctrineMigrations',
                'table_name' => 'doctrine_migration_versions',
                'directory' => sys_get_temp_dir()
            ]
        ]);

        $this->assertCount(5, $container['console']->all('migrations'));
    }
}
