<?php

namespace Sergiors\Taxonomy\Tests\Functional;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\EventManager;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Annotations\AnnotationReader;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataFactory;
use Sergiors\Taxonomy\Configuration\Metadata\Driver\AnnotationDriver;
use Sergiors\Taxonomy\EventListener\ClassMetadataListener;
use Sergiors\Taxonomy\EventListener\PostLoadListener;
use Sergiors\Taxonomy\EventListener\PreFlushListener;
use Sergiors\Taxonomy\EventListener\PreUpdateListener;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function __construct()
    {
        $this->container = new \Pimple();

        $container = $this->container;

        $container['doctrine_dbal.configuration'] = [
            'driver' => 'pdo_sqlite',
            'memory' => true
        ];

        $container['doctrine_dbal.event_manager'] = $container->share(function () {
            return new EventManager();
        });

        $container['doctrine_dbal.connection'] = $container->share(function ($container) {
            return DriverManager::getConnection(
                $container['doctrine_dbal.configuration'],
                null,
                $container['doctrine_dbal.event_manager']
            );
        });

        $container['doctrine_orm.entity_manager'] = $container->share(function ($container) {
            return EntityManager::create(
                $container['doctrine_dbal.connection'],
                $container['doctrine_orm.configuration'],
                $container['doctrine_dbal.event_manager']
            );
        });

        $container['doctrine_orm.cache_driver'] = $container->share(function () {
            return new FilesystemCache(sys_get_temp_dir()."/doctrine");
        });

        $container['doctrine_orm.configuration'] = $container->share(function ($container) {
            return Setup::createAnnotationMetadataConfiguration(
                [__DIR__.'/../Fixture'],
                true,
                sys_get_temp_dir(),
                $container['doctrine_orm.cache_driver'],
                false
            );
        });

        $container['doctrine_dbal.event_manager'] = $container
            ->extend('doctrine_dbal.event_manager', function ($eventManager, $container) {
                $listeners = $container['taxonomy.listeners'];

                return array_reduce($listeners, function (EventManager $eventManager, $listener) {
                    $eventManager->addEventSubscriber($listener);
                    return $eventManager;
                }, $eventManager);
            });

        $container['taxonomy.listeners'] = $container->share(function ($container) {
            return [
                new PreFlushListener($container['taxonomy.metadata_factory']),
                new PreUpdateListener($container['taxonomy.metadata_factory']),
                new PostLoadListener($container['taxonomy.metadata_factory']),
                new ClassMetadataListener($container['taxonomy.metadata_factory'])
            ];
        });

        $container['doctrine_orm.annotation_reader'] = $container->share(function () {
            return new AnnotationReader();
        });

        $container['taxonomy.annotation_driver'] = $container->share(function ($container) {
            return new AnnotationDriver($container['doctrine_orm.annotation_reader']);
        });

        $container['taxonomy.metadata_factory'] = $container->share(function ($container) {
            return new ClassMetadataFactory(
                $container['taxonomy.annotation_driver'],
                $container['doctrine_orm.cache_driver']
            );
        });
    }

    public function setUp()
    {
        $pdo = $this->container['doctrine_orm.entity_manager']
            ->getConnection()
            ->getWrappedConnection();

        $pdo->exec('
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                name VARCHAR (200),
                metadata TEXT
            )
        ');
    }

    public function tearDown()
    {
        $pdo = $this->container['doctrine_orm.entity_manager']
            ->getConnection()
            ->getWrappedConnection();

        $pdo->exec('DROP TABLE IF EXISTS users');
    }
}
