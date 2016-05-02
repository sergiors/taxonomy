<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ClassMetadata implements ClassMetadataInterface, \Serializable
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

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->className,
            $this->embeddedClasses
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->className, $this->embeddedClasses) = unserialize($serialized);
    }
}
