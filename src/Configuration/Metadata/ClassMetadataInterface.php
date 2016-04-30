<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
interface ClassMetadataInterface
{
    /**
     * @return array
     */
    public function getEmbeddedClasses();

    /**
     * @param EmbeddedMetadataInterface $embeddedMetadata
     */
    public function addEmbeddedClass(EmbeddedMetadataInterface $embeddedMetadata);
}
