<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Metadata\MergeableClassMetadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ClassMetadata extends MergeableClassMetadata implements ClassMetadataInterface
{
    /**
     * @var array
     */
    private $embeddedClasses = [];

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
    public function mapEmbedded($propertyName, array $mapping)
    {
        $this->embeddedClasses[$propertyName] = [
            'class' => $mapping['class'],
            'column' => $mapping['column']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function addNestedEmbedded($propertyName, array $mapping)
    {
        $this->embeddedClasses[$propertyName]['embeddable'][] = $mapping;
    }
}
