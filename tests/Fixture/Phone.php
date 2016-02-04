<?php

namespace Sergiors\Taxonomy\Tests\Fixture;

use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

/**
 * @Taxonomy\Embeddable
 */
class Phone
{
    /**
     * @Taxonomy\Index
     */
    private $number;

    /**
     * @Taxonomy\Index(name="actived")
     */
    private $active;

    public function __construct()
    {
        $this->active = false;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function setNumber($number)
    {
        $this->number = preg_replace('/\D+/', '', $number);
    }

    public function setActive($active)
    {
        if (!is_bool($active)) {
            throw new \UnexpectedValueException();
        }

        $this->active = $active;
    }
}
