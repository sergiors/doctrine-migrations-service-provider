<?php
namespace Sergiors\Silex\Provider;

use Silex\Application;
use Silex\WebTestCase;
use Silex\Provider\DoctrineServiceProvider;

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
        $app->register(new DoctrineMigrationsServiceProvider());

        $this->assertCount(6, $app['console']->all('migrations'));
    }

    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }
}
