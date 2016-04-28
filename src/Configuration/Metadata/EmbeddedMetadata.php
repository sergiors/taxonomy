<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Metadata\PropertyMetadata;
use Doctrine\ORM\Mapping\Column;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class EmbeddedMetadata extends PropertyMetadata implements EmbeddedMetadataInterface
{
    /**
     * @var string
     */
    private $classAttr;

    /**
     * @var Column
     */
    private $columnAttr;

    /**
     * @var EmbeddableMetadataInterface[]
     */
    private $embeddableMetadata = [];

    /**
     * @param string      $class
     * @param string      $name
     * @param string      $classAttr
     * @param Column|null $columnAttr
     */
    public function __construct($class, $name, $classAttr, Column $columnAttr = null)
    {
        parent::__construct($class, $name);

        $this->classAttr = $classAttr;
        $this->columnAttr = $columnAttr;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->classAttr;
    }

    /**
     * @return Column
     */
    public function getColumn()
    {
        return $this->columnAttr;
    }

    public function getEmbeddableList()
    {
        return $this->embeddableMetadata;
    }

    /**
     * @param EmbeddableMetadataInterface $metadata
     */
    public function addEmbeddableMetadata(EmbeddableMetadataInterface $metadata)
    {
        $this->embeddableMetadata[] = $metadata;
    }
}
