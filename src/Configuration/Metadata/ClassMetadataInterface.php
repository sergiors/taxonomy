<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
interface ClassMetadataInterface
{
    /**
     * @return string
     */
    public function getDiscriminatorColumn();

    /**
     * @return array
     */
    public function getEmbeddedClasses();

    /**
     * @param string $name
     */
    public function setDiscriminatorColumn($name);

    /**
     * @param array $mapping
     */
    public function mapEmbedded(array $mapping);

    /**
     * @param string $name
     * @param array $mapping
     */
    public function addNestedEmbedded($name, array $mapping);
}
