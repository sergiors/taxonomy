<?php

namespace Sergiors\Taxonomy\Tests\Fixture;

use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

/**
 * @Taxonomy\Embeddable
 */
class UserMetadata
{
    /**
     * @Taxonomy\Embedded(class="Sergiors\Taxonomy\Tests\Fixture\Email")
     *
     * @var Email
     */
    private $email;

    /**
     * @Taxonomy\Embedded(class="Sergiors\Taxonomy\Tests\Fixture\Phone")
     *
     * @var Phone
     */
    private $mobile;

    /**
     * @Taxonomy\Embedded(class="Sergiors\Taxonomy\Tests\Fixture\Address")
     *
     * @var Address
     */
    private $address;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(Email $email)
    {
        $this->email = $email;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setMobile(Phone $mobile)
    {
        $this->mobile = $mobile;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress(Address $address)
    {
        $this->address = $address;
    }
}
