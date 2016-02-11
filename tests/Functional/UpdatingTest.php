<?php

namespace Sergiors\Taxonomy\Tests\Functional;

use Sergiors\Taxonomy\Tests\Fixture\User;
use Sergiors\Taxonomy\Tests\Fixture\Email;
use Sergiors\Taxonomy\Tests\Fixture\Phone;

class UpdatingTest extends TestCase
{
    /**
     * @before
     */
    public function insertUsers()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $faker = \Faker\Factory::create();

        $phone = new Phone();
        $phone->setNumber('4792030815');

        $user = new User();
        $user->setName($faker->name);
        $user->setEmail(new Email('kirk@enterprise.com'));
        $user->setMobile($phone);

        $em->persist($user);
        $em->flush();
        $em->clear();
    }

    /**
     * @test
     */
    public function shouldUpdateEmail()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $faker = \Faker\Factory::create();

        $user = $em
            ->getRepository(User::class)
            ->findOneById(1);

        $oldEmail = clone $user->getEmail();
        $oldMobile = clone $user->getMobile();
        $newEmail = new Email($faker->email);

        $user->setEmail($newEmail);

        $em->persist($user);
        $em->flush();
        $em->clear();

        $userUpdated = $em
            ->getRepository(User::class)
            ->findOneById(1);

        $this->assertNotEquals($oldEmail, $userUpdated->getEmail());
        $this->assertEquals($newEmail, $userUpdated->getEmail());
        $this->assertEquals($oldMobile, $userUpdated->getMobile());
    }
}
