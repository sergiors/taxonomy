<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ClassMetadata implements ClassMetadataInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $embeddedClasses = [];

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmbeddedClasses()
    {
        return $this->embeddedClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function addEmbeddedClass(EmbeddedMetadataInterface $embeddedMetadata)
    {
        $this->embeddedClasses[] = $embeddedMetadata;
    }
}
