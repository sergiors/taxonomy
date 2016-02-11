<?php

namespace Sergiors\Taxonomy\Tests\Functional;

use Sergiors\Taxonomy\Tests\Fixture\User;
use Sergiors\Taxonomy\Tests\Fixture\State;
use Sergiors\Taxonomy\Tests\Fixture\City;
use Sergiors\Taxonomy\Tests\Fixture\Address;

class DeepTest extends TestCase
{
    /**
     * @before
     */
    public function insertUsers()
    {
        $em = $this->container['doctrine_orm.entity_manager'];

        $state = new State();
        $state->setName('Santa Catarina');
        $city = new City();
        $city->setName('Florianopolis');
        $city->setState($state);

        $address = new Address();
        $address->setCity($city);

        $user = new User();
        $user->setAddress($address);

        $em->persist($user);
        $em->flush();
        $em->clear();
    }

    /**
     * @test
     */
    public function readingUserWithAddress()
    {
        $em = $this->container['doctrine_orm.entity_manager'];
        $user = $em
            ->getRepository(User::class)
            ->findOneById(1);

        $this->assertInstanceOf(Address::class, $user->getAddress());
        $this->assertInstanceOf(City::class, $user->getAddress()->getCity());
        $this->assertInstanceOf(State::class, $user->getAddress()->getCity()->getState());

        $this->assertEquals($user->getAddress()->getCity()->getName(), 'Florianopolis');
        $this->assertEquals($user->getAddress()->getCity()->getState()->getName(), 'Santa Catarina');
    }
}
