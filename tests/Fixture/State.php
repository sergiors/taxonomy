<?php

namespace Sergiors\Taxonomy\Tests\Fixture;

use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

/**
 * @Taxonomy\Embeddable
 */
class State
{
    /**
     * @Taxonomy\Index
     *
     * @var string
     */
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
