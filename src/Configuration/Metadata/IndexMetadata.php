<?php

namespace Sergiors\Taxonomy\Configuration\Metadata;

use Metadata\PropertyMetadata;
use Sergiors\Taxonomy\Configuration\Annotation\Index;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class IndexMetadata implements IndexMetadataInterface
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
    private $nameAttribute;

    /**
     * @var string
     */
    private $typeAttribute;

    private $reflProperty;

    /**
     * @param string      $className
     * @param string      $propertyName
     * @param string|null $nameAttribute
     * @param string|null $typeAttribute
     */
    public function __construct($className, $propertyName, $nameAttribute = null, $typeAttribute = null)
    {
        $this->className = $className;
        $this->propertyName = $propertyName;
        $this->nameAttribute = $nameAttribute;
        $this->typeAttribute = $typeAttribute;

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
    public function getNameAttribute()
    {
        return $this->nameAttribute ?: $this->propertyName;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeAttribute()
    {
        return $this->typeAttribute;
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
