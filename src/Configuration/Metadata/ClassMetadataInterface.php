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
     * @param string $propertyName
     * @param array  $mapping
     */
    public function mapEmbedded($propertyName, array $mapping);

    /**
     * @param string $propertyName
     * @param array  $mapping
     */
    public function addNestedEmbedded($propertyName, array $mapping);
}
