<?php

namespace Sergiors\Silex\Tests\Provider;

use Silex\Application;
use Silex\WebTestCase;
use Silex\Provider\DoctrineServiceProvider;
use Sergiors\Silex\Provider\ConsoleServiceProvider;
use Sergiors\Silex\Provider\DoctrineMigrationsServiceProvider;

class DoctrineMigrationsServiceProviderTest extends WebTestCase
{
    /**
     * @test
     */
    public function shouldReturnTheCommands()
    {
        $app = $this->createApplication();
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

    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }
}
