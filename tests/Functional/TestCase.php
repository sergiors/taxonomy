<?php
namespace Sergiors\Taxonomy\Functional;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\EventManager;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\MetadataFactory;
use Sergiors\Taxonomy\Configuration\Metadata\Driver\AnnotationDriver;
use Sergiors\Taxonomy\EventListener\ClassMetadataListener;
use Sergiors\Taxonomy\EventListener\PostLoadListener;
use Sergiors\Taxonomy\EventListener\PreFlushListener;

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

        $container['doctrine_orm.configuration'] = $container->share(function () {
            return Setup::createAnnotationMetadataConfiguration(
                [__DIR__.'/../Fixtures'],
                true,
                sys_get_temp_dir(),
                new ArrayCache(),
                false
            );
        });

        $container['doctrine_dbal.event_manager'] = $container
            ->extend('doctrine_dbal.event_manager', function ($eventManager, $container) {
                $eventManager->addEventSubscriber($container['taxonomy.pre_flush_listener']);
                $eventManager->addEventSubscriber($container['taxonomy.post_load_listener']);
                $eventManager->addEventSubscriber($container['taxonomy.class_metadata_listener']);
                return $eventManager;
            });

        $container['doctrine_orm.annotation_reader'] = $container->share(function () {
            return new AnnotationReader();
        });

        $container['taxonomy.annotation_driver'] = $container->share(function ($container) {
            return new AnnotationDriver($container['doctrine_orm.annotation_reader']);
        });

        $container['taxonomy.metadata_factory'] = $container->share(function ($container) {
            return new MetadataFactory($container['taxonomy.annotation_driver']);
        });

        $container['taxonomy.pre_flush_listener'] = $container->share(function ($container) {
            return new PreFlushListener($container['taxonomy.metadata_factory']);
        });

        $container['taxonomy.post_load_listener'] = $container->share(function ($container) {
            return new PostLoadListener($container['taxonomy.metadata_factory']);
        });

        $container['taxonomy.class_metadata_listener'] = $container->share(function ($container) {
            return new ClassMetadataListener($container['taxonomy.metadata_factory']);
        });
    }
}