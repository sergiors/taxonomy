<?php
namespace Sergiors\Taxonomy\Configuration\Metadata;

use Doctrine\ORM\Mapping\Column;
use Metadata\MergeableClassMetadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ClassMetadata extends MergeableClassMetadata implements ClassMetadataInterface
{
    private $taxonomy;

    private $column;

    public function getName()
    {
        return $this->name;
    }

    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function setTaxonomy($property)
    {
        $this->taxonomy = $property;
    }

    public function setColumn(Column $column)
    {
        $this->column = $column;
    }

    public function addTaxon($property, $class)
    {
        $propertyMetadata = new PropertyMetadata($this->name, $property, $class);
        $this->addPropertyMetadata($propertyMetadata);
    }
}