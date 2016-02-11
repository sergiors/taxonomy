<?php

namespace Sergiors\Taxonomy\Tests\Fixture;

use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

/**
 * @Taxonomy\Embeddable
 */
class City
{
    /**
     * @Taxonomy\Index
     *
     * @var string
     */
    private $name;

    /**
     * @Taxonomy\Embedded(class="Sergiors\Taxonomy\Tests\Fixture\State")
     *
     * @var State
     */
    private $state;

    public function getName()
    {
        return $this->name;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setState(State $state)
    {
        $this->state = $state;
    }
}
