<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Metadata\MergeableClassMetadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ClassMetadata extends MergeableClassMetadata implements ClassMetadataInterface
{
    /**
     * @var string
     */
    private $discriminatorColumn;

    /**
     * @var array
     */
    private $embeddedClasses = [];

    /**
     * {@inheritdoc}
     */
    public function getDiscriminatorColumn()
    {
        return $this->discriminatorColumn;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmbeddedClasses()
    {
        return $this->embeddedClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function setDiscriminatorColumn($name)
    {
        $this->discriminatorColumn = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function mapEmbedded(array $mapping)
    {
        $this->embeddedClasses[$mapping['fieldName']] = [
            'class' => $mapping['class'],
            'column' => $mapping['column']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function addNestedEmbedded($name, array $mapping)
    {
        $this->embeddedClasses[$name]['embedded'][] = $mapping;
    }
}
