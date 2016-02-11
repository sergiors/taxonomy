<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
interface EmbeddedMetadataInterface extends EmbeddableMetadataInterface
{
    public function getClass();

    public function getColumn();

    public function getEmbeddableList();

    public function addEmbeddableMetadata(EmbeddableMetadataInterface $metadata);
}
