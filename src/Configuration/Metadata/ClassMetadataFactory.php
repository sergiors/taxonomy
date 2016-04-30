<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Doctrine\Common\Cache\Cache;
use Sergiors\Taxonomy\Configuration\Metadata\Driver\MappingDriverInterface;

class ClassMetadataFactory
{
    /**
     * @var MappingDriverInterface
     */
    private $mappingDriver;

    /**
     * @var Cache|null
     */
    private $cacheDriver;

    /**
     * @param MappingDriverInterface $mappingDriver
     * @param Cache|null             $cacheDriver
     */
    public function __construct(MappingDriverInterface $mappingDriver, Cache $cacheDriver = null)
    {
        $this->mappingDriver = $mappingDriver;
        $this->cacheDriver = $cacheDriver;
    }

    /**
     * @param string $className
     *
     * @return ClassMetadataInterface
     */
    public function getMetadataForClass($className)
    {
        return $this->mappingDriver->loadMetadataForClass($className);
    }
}
