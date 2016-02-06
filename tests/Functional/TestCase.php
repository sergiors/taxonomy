<?php

namespace Sergiors\Taxonomy\Tests\Functional;

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
use Sergiors\Taxonomy\EventListener\PreUpdateListener;
use Sergiors\Taxonomy\Tests\Fixture\User;
use Sergiors\Taxonomy\Tests\Fixture\Email;
use Sergiors\Taxonomy\Tests\Fixture\Phone;

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
                [__DIR__.'/../Fixture'],
                true,
                sys_get_temp_dir(),
                new ArrayCache(),
                false
            );
        });

        $container['doctrine_dbal.event_manager'] = $container
            ->extend('doctrine_dbal.event_manager', function ($eventManager, $container) {
                $listeners = $container['taxonomy.listeners'];

                foreach ($listeners as $listener) {
                    $eventManager->addEventSubscriber($listener);
                }

                return $eventManager;
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
            return new MetadataFactory($container['taxonomy.annotation_driver']);
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
                phone_metadata TEXT,
                email_metadata TEXT
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

    /**
     * @before
     */
    public function insertUsers()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setName('Sérgio');
        $user->setEmail(new Email($faker->email));

        $phone = new Phone();
        $phone->setNumber('4792030815');

        $user2 = new User();
        $user2->setName($faker->name);
        $user2->setEmail(new Email('kirk@enterprise.com'));
        $user2->setMobile($phone);

        $em->persist($user);
        $em->persist($user2);
        $em->flush();
        $em->clear();
    }
}
