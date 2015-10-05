<?php
namespace Sergiors\Taxonomy\Configuration\Metadata;

use Metadata\PropertyMetadata as BasePropertyMetadata;

class PropertyMetadata extends BasePropertyMetadata implements PropertyMetadataInterface
{
    private $taxon;

    public function __construct($class, $property, $taxon)
    {
        parent::__construct($class, $property);

        if (!class_exists($taxon)) {
            throw new \InvalidArgumentException();
        }

        $this->taxon = $taxon;
    }

    public function getTaxonClass()
    {
        return $this->taxon;
    }
}