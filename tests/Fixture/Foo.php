<?php

namespace Sergiors\Taxonomy\Tests\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

/**
 * @ORM\Entity
 */
class Foo
{
    /**
     * @Taxonomy\Embedded(
     *     class="Sergiors\Taxonomy\Tests\Fixture\City",
     *     column=@ORM\Column(name="metadata", type="json_array")
     * )
     *
     * @var $city
     */
    private $city;
}
