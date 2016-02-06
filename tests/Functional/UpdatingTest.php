<?php

namespace Sergiors\Taxonomy\Tests\Functional;

use Sergiors\Taxonomy\Tests\Fixture\User;
use Sergiors\Taxonomy\Tests\Fixture\Email;

class UpdatingTest extends TestCase
{

    /**
     * @test
     */
    public function shouldUpdateEmail()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $faker = \Faker\Factory::create();

        $user = $em
            ->getRepository(User::class)
            ->findOneById(2);

        $oldEmail = clone $user->getEmail();
        $oldMobile = clone $user->getMobile();
        $newEmail = new Email($faker->email);

        $user->setEmail($newEmail);

        $em->persist($user);
        $em->flush();
        $em->clear();

        $userUpdated = $em
            ->getRepository(User::class)
            ->findOneById(2);

        $this->assertNotEquals($oldEmail, $userUpdated->getEmail());
        $this->assertEquals($newEmail, $userUpdated->getEmail());
        $this->assertEquals($oldMobile, $userUpdated->getMobile());
    }
}
