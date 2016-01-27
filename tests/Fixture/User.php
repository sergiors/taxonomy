<?php

namespace Sergiors\Taxonomy\Fixture;

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
     *     class="Sergiors\Taxonomy\Fixture\Email",
     *     column=@Taxonomy\Column(name="email_metadata")
     * )
     */
    private $email;

    /**
     * @Taxonomy\Embedded(
     *     class="Sergiors\Taxonomy\Fixture\Phone",
     *     column=@Taxonomy\Column(name="phone_metadata")
     * )
     */
    private $mobile;

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
}
