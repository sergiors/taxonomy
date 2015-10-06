<?php
namespace Sergiors\Taxonomy\Functional;

use Sergiors\Taxonomy\Fixture\User;
use Sergiors\Taxonomy\Fixture\Email;
use Sergiors\Taxonomy\Fixture\Phone;
use Sergiors\Taxonomy\Fixture\Order;

class IntegrationTest extends TestCase
{
    public function setUp()
    {
        $pdo = $this->container['doctrine_orm.entity_manager']
            ->getConnection()
            ->getWrappedConnection();

        $pdo->exec('
            CREATE TABLE IF NOT EXISTS user (
                id INTEGER PRIMARY KEY,
                name VARCHAR (200),
                taxons TEXT
            )
        ');

        $pdo->exec('
            CREATE TABLE IF NOT EXISTS sales_order (
                id INTEGER PRIMARY KEY
            )
        ');
    }

    public function tearDown()
    {
        $pdo = $this->container['doctrine_orm.entity_manager']
            ->getConnection()
            ->getWrappedConnection();

        $pdo->exec('DROP TABLE IF EXISTS user');
        $pdo->exec('DROP TABLE IF EXISTS sales_order');
    }

    /**
     * @before
     */
    public function insertUsers()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->setName($faker->name);
        $user->setEmail(new Email($faker->email));

        $phone = new Phone();
        $phone->setNumber($faker->phoneNumber);
        $user2 = new User();
        $user2->setName($faker->name);
        $user2->setEmail(new Email($faker->email));
        $user2->setPhone($phone);

        $em->persist($user);
        $em->persist($user2);
        $em->flush();

        $em->detach($user);
        $em->detach($user2);
    }

    /**
     * @test
     */
    public function shouldReturnUsers()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $users = $em
            ->getRepository(User::class)
            ->findAll();

        $this->assertCount(2, $users);
    }

    /**
     * @test
     */
    public function shouldReturnEmailInstance()
    {
        $em = $this->container['doctrine_orm.entity_manager'];
        $user = $em
            ->getRepository(User::class)
            ->findOneById(1);

        $this->assertInstanceOf(Email::class, $user->getEmail());
    }

    /**
     * @test
     */
    public function shouldInsertWithoutTaxonomy()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $order = new Order();
        $em->persist($order);
        $em->flush();

        $em->detach($order);

        $order = $em
            ->getRepository(Order::class)
            ->findOneById(1);
    }
}