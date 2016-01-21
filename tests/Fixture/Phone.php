<?php

namespace Sergiors\Taxonomy\Fixture;

use Sergiors\Taxonomy\Configuration\Annotation\Taxon;

class Phone
{
    /**
     * @Taxon
     */
    private $number;

    /**
     * @Taxon
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
}
