<?php

namespace Sergiors\Taxonomy\Tests\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    private $id;

    /**
     * @ORM\Column(name="name")
     */
    private $name;

    /**
     * @Taxonomy\Embedded(
     *     class="Sergiors\Taxonomy\Tests\Fixture\Email",
     *     column=@Taxonomy\Column(name="email_metadata")
     * )
     *
     * @var Email
     */
    private $email;

    /**
     * @Taxonomy\Embedded(
     *     class="Sergiors\Taxonomy\Tests\Fixture\Phone",
     *     column=@Taxonomy\Column(name="phone_metadata")
     * )
     *
     * @var Phone
     */
    private $mobile;

    /**
     * @Taxonomy\Embedded(
     *     class="Sergiors\Taxonomy\Tests\Fixture\Address",
     *     column=@Taxonomy\Column(name="address_metadata")
     * )
     *
     * @var Address
     */
    private $address;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail(Email $email)
    {
        $this->email = $email;
    }

    public function setMobile(Phone $mobile)
    {
        $this->mobile = $mobile;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }
}
