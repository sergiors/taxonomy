<?php
namespace Sergiors\Taxonomy\Fixture;

class Email
{
    private $address;

    public function __construct($address = null)
    {
        $this->setAddress($address);
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $address = mb_strtolower(trim($address));
        $this->address = $address;
    }
}
