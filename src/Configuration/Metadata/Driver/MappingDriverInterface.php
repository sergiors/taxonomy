<?php

namespace Sergiors\Taxonomy\Configuration\Metadata\Driver;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
interface MappingDriverInterface
{
    /**
     * @param string $className
     * @return \Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataInterface
     */
    public function loadMetadataForClass($className);
}
