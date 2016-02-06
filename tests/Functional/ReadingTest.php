<?php

namespace Sergiors\Taxonomy\Tests\Functional;

use Sergiors\Taxonomy\Tests\Fixture\User;
use Sergiors\Taxonomy\Tests\Fixture\Email;
use Sergiors\Taxonomy\Tests\Fixture\Phone;

class ReadingTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInstanceOfDateTime()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $user = $em
            ->getRepository(User::class)
            ->findOneById(1);

        $this->assertInstanceOf(\DateTime::class, $user->getEmail()->getCreatedAt());
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
            ->findOneById(2);

        $this->assertInstanceOf(Email::class, $user->getEmail());
        $this->assertInstanceOf(Phone::class, $user->getMobile());

        $this->assertEquals($user->getMobile()->getNumber(), '4792030815');
    }

    /**
     * @test
     */
    public function shouldReturnPhoneInstanceWithNullValues()
    {
        $em = $this->container['doctrine_orm.entity_manager'];
        $user = $em
            ->getRepository(User::class)
            ->findOneById(1);

        $this->assertInstanceOf(Phone::class, $user->getMobile());

        $this->assertNull($user->getMobile()->getNumber());
    }
}
