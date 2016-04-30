<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
interface EmbeddedMetadataInterface extends EmbeddableMetadataInterface, MetadataInterface
{
    /**
     * @return string
     */
    public function getClassAttribute();

    /**
     * @return \Doctrine\ORM\Mapping\Column
     */
    public function getColumnAttribute();

    /**
     * @return array
     */
    public function getEmbeddableClasses();

    /**
     * @param EmbeddableMetadataInterface $embeddableMetadata
     */
    public function addEmbeddableClass(EmbeddableMetadataInterface $embeddableMetadata);
}
