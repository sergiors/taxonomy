<?php

namespace Sergiors\Taxonomy\Tests\Fixture;

use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

/**
 * @Taxonomy\Embeddable
 */
class Email
{
    /**
     * @Taxonomy\Index(name="email_address")
     */
    private $address;

    /**
     * @Taxonomy\Index(type="datetime")
     *
     * @var \DateTime
     */
    private $createdAt;

    private $confirmed = false;

    public function __construct($address = null)
    {
        $this->createdAt = new \DateTime();
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }
}
