<?php

namespace Sergiors\Taxonomy\Tests\Functional;

use Sergiors\Taxonomy\Tests\Fixture\User;
use Sergiors\Taxonomy\Tests\Fixture\Email;
use Sergiors\Taxonomy\Tests\Fixture\Phone;

class IntegrationTest extends TestCase
{
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
        $user->setName($faker->name);
        $user->setEmail(new Email($faker->email));

        $phone = new Phone();
        $phone->setNumber($faker->phoneNumber);

        $user2 = new User();
        $user2->setName($faker->name);
        $user2->setEmail(new Email($faker->email));
        $user2->setMobile($phone);

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
        $this->assertInstanceOf(Phone::class, $user->getMobile());

        $this->assertNull($user->getMobile()->getNumber());
    }
}
