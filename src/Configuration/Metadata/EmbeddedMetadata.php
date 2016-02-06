<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Metadata\PropertyMetadata;
use Sergiors\Taxonomy\Configuration\Annotation\Column;

class EmbeddedMetadata extends PropertyMetadata
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
     * @var EmbeddableMetadata[]
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
     * @param EmbeddableMetadata $metadata
     */
    public function addEmbeddableMetadata(EmbeddableMetadata $metadata)
    {
        $this->embeddableMetadata[] = $metadata;
    }
}
