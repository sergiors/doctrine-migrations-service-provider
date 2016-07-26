<?php

namespace Sergiors\Silex\Tests\Provider;

use Pimple\Container;
use Silex\Provider\DoctrineServiceProvider;
use Sergiors\Silex\Provider\ConsoleServiceProvider;
use Sergiors\Silex\Provider\DoctrineMigrationsServiceProvider;

class DoctrineMigrationsServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnTheCommands()
    {
        $app = new Container();
        $app->register(new DoctrineServiceProvider());
        $app->register(new ConsoleServiceProvider());
        $app->register(new DoctrineMigrationsServiceProvider(), [
            'migrations.options' => [
                'name' => 'Doctrine Migrations',
                'namespace' => 'DoctrineMigrations',
                'table_name' => 'doctrine_migration_versions',
                'directory' => sys_get_temp_dir()
            ]
        ]);

        $this->assertCount(5, $app['console']->all('migrations'));
    }
}
