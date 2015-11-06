<?php
namespace Sergiors\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Doctrine\DBAL\Migrations\Configuration\Configuration;

/**
 * @author Sérgio Rafael Siqueira <sergio@gmail.com>
 */
class DoctrineMigrationsServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        if (!isset($app['db'])) {
            throw new \LogicException(
                'You must register the DoctrineServiceProvider to use the DoctrineMigrationsServiceProvider.'
            );
        }

        if (!isset($app['console'])) {
            throw new \LogicException(
                'You must register the ConsoleServiceProvider to use the DoctrineMigrationsServiceProvider.'
            );
        }

        $app['migrations.configuration'] = $app->share(function (Application $app) {
            $options = $app['migrations.options'];

            $config = new Configuration($app['db']);
            $config->setName($options['name']);
            $config->setMigrationsTableName($options['table_name']);
            $config->setMigrationsDirectory($options['directory']);
            $config->setMigrationsNamespace($options['namespace']);
            $config->registerMigrationsFromDirectory($options['directory']);

            return $config;
        });

        $app['console'] = $app->share($app->extend('console', function ($console) use ($app) {
            $console->getHelperSet()->set(new QuestionHelper());

            $console->add(new ExecuteCommand());
            $console->add(new GenerateCommand());
            $console->add(new MigrateCommand());
            $console->add(new StatusCommand());
            $console->add(new VersionCommand());

            if ($console->getHelperSet()->has('em')) {
                $console->add(new DiffCommand());
            }

            $commands = $console->all('migrations');
            foreach ($commands as $command) {
                $command->setMigrationConfiguration($app['migrations.configuration']);
            }

            return $console;
        }));

        $app['migrations.options'] = [
            'name' =>  null,
            'namespace' => 'DoctrineMigrations',
            'table_name' => 'doctrine_migration_versions',
            'directory' => null
        ];
    }

    public function boot(Application $app)
    {
    }
}
