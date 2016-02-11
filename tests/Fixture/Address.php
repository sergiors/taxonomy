<?php

namespace Sergiors\Taxonomy\Tests\Fixture;

use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

/**
 * @Taxonomy\Embeddable
 */
class Address
{
    /**
     * @Taxonomy\Embedded(class="Sergiors\Taxonomy\Tests\Fixture\City")
     *
     * @var City
     */
    private $city;

    public function getCity()
    {
        return $this->city;
    }

    public function setCity(City $city)
    {
        $this->city = $city;
    }
}
