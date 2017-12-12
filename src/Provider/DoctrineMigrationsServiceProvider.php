<?php

namespace Sergiors\Pimple\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
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
    public function register(Container $container)
    {
        if (!isset($container['db'])) {
            throw new \LogicException(
                'You must register the DoctrineServiceProvider to use the DoctrineMigrationsServiceProvider.'
            );
        }

        if (!isset($container['console'])) {
            throw new \LogicException(
                'You must register the ConsoleServiceProvider to use the DoctrineMigrationsServiceProvider.'
            );
        }

        $container['migrations.options'] = [
            'name'       => null,
            'namespace'  => 'DoctrineMigrations',
            'table_name' => 'doctrine_migration_versions',
            'directory'  => null,
        ];

        $container['migrations.configuration'] = function () use ($container) {
            $options = $container['migrations.options'];

            $config = new Configuration($container['db']);
            $config->setName($options['name']);
            $config->setMigrationsTableName($options['table_name']);
            $config->setMigrationsDirectory($options['directory']);
            $config->setMigrationsNamespace($options['namespace']);
            $config->registerMigrationsFromDirectory($options['directory']);

            return $config;
        };

        $container['console'] = $container->extend('console', function ($console) use ($container) {
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
                $command->setMigrationConfiguration(
                    $container['migrations.configuration']
                );
            }

            return $console;
        });
    }
}
