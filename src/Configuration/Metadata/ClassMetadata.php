<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Metadata\MergeableClassMetadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ClassMetadata extends MergeableClassMetadata implements ClassMetadataInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEmbeddedList()
    {
        return $this->propertyMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function addEmbeddedMetadata(EmbeddedMetadata $metadata)
    {
        parent::addPropertyMetadata($metadata);
    }
}
