<?php
namespace Sergiors\Taxonomy;

use Sergiors\Taxonomy\Mapping\Taxonomy;

class User
{
    /**
     * @Taxonomy(defaultClass="Phone")
     */
    private $phone;

    /**
     * @return Phone
     */
    public function getPhone()
    {
        return $phone;
    }

    /**
     * @param Phone $phone
     */
    public function setPhone(Phone $phone)
    {
        return $this->phone;
    }
}
