<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
interface IndexMetadataInterface extends EmbeddableMetadataInterface, MetadataInterface
{
    /**
     * @return string
     */
    public function getNameAttribute();

    /**
     * @return string
     */
    public function getTypeAttribute();
}
