<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Doctrine\ORM\Mapping\Column;

interface ClassMetadataInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getTaxonomy();

    /**
     * @return Column
     */
    public function getColumn();

    /**
     * @param string $property
     */
    public function setTaxonomy($property);

    /**
     * @param Column $column
     */
    public function setColumn(Column $column);

    /**
     * @param string $property
     * @param string $class
     */
    public function addTaxon($property, $class);
}
