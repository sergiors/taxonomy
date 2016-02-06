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
    public function getEmbeddedList();

    /**
     * @param EmbeddedMetadata $metadata
     */
    public function addEmbeddedMetadata(EmbeddedMetadata $metadata);
}
