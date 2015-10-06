<?php
namespace Sergiors\Taxonomy\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Sergiors\Taxonomy\Configuration\Annotation\Taxonomy;
use Sergiors\Taxonomy\Configuration\Annotation\Taxon;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
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
     * @Taxonomy(column=@ORM\Column(name="taxons"))
     */
    private $taxonomy;

    /**
     * @Taxon(class="Sergiors\Taxonomy\Fixture\Email"))
     */
    private $email;

    /**
     * @Taxon(class="Sergiors\Taxonomy\Fixture\Phone"))
     */
    private $phone;

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

    public function getPhone()
    {
        return $this->phone;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

}
