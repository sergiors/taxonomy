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
     *     class="Sergiors\Taxonomy\Tests\Fixture\UserMetadata",
     *     column=@ORM\Column(name="metadata")
     * )
     *
     * @var UserMetadata
     */
    private $metadata;

    public function __construct()
    {
        $this->metadata = new UserMetadata();
    }

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
        return $this->metadata->getEmail();
    }

    public function getMobile()
    {
        return $this->metadata->getMobile();
    }

    public function getAddress()
    {
        return $this->metadata->getAddress();
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
        $this->metadata->setEmail($email);
    }

    public function setMobile(Phone $mobile)
    {
        $this->metadata->setMobile($mobile);
    }

    public function setAddress($address)
    {
        $this->metadata->setAddress($address);
    }
}
