<?php
namespace Sergiors\Metadata;

use Sergiors\Metadata\Mapping\Metadata;

class User
{
    /**
     * @Metadata(defaultClass="Phone")
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
