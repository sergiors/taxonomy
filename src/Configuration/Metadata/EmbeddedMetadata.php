<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class EmbeddedMetadata implements EmbeddedMetadataInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $classAttribute;

    /**
     * @var string
     */
    private $columnAttribute;

    /**
     * @var array
     */
    private $embeddableClasses = [];

    private $reflProperty;

    /**
     * @param string $className
     * @param string $propertyName
     * @param string $classAttribute
     * @param null   $columnAttribute
     */
    public function __construct(
        $className,
        $propertyName,
        $classAttribute,
        $columnAttribute = null
    ) {
        $this->className = $className;
        $this->propertyName = $propertyName;
        $this->classAttribute = $classAttribute;
        $this->columnAttribute = $columnAttribute;

        $this->reflProperty = new \ReflectionProperty($className, $propertyName);
        $this->reflProperty->setAccessible(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassAttribute()
    {
        return $this->classAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnAttribute()
    {
        return $this->columnAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmbeddableClasses()
    {
        return $this->embeddableClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function addEmbeddableClass(EmbeddableMetadataInterface $embeddableMetadata)
    {
        $this->embeddableClasses[] = $embeddableMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($entity)
    {
        return $this->reflProperty->getValue($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($entity, $value)
    {
        $this->reflProperty->setValue($entity, $value);
    }
}
