<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\Common\EventSubscriber;
use Metadata\MetadataFactory;
use Sergiors\Taxonomy\Type\Type;
use ReflectionClass;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
abstract class ObjectWalkerListener implements EventSubscriber
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @param MetadataFactory $metadataFactory
     */
    public function __construct(MetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @param array $scheduledEntity
     */
    protected function applyValueObject(array $scheduledEntity)
    {
        foreach ($scheduledEntity as $entity) {
            $entityClass = get_class($entity);
            $classMetadata = $this->metadataFactory->getMetadataForClass($entityClass);

            $this->applyEmbeddedValueToEntity($classMetadata->getEmbeddedClasses(), $entity);
        }
    }

    /**
     * @param array  $embeddedClasses
     * @param object $entity
     */
    private function applyEmbeddedValueToEntity(array $embeddedClasses, $entity)
    {
        if (!is_object($entity)) {
            throw new \UnexpectedValueException();
        }

        $entityClass = get_class($entity);
        $reflClass = new ReflectionClass($entityClass);

        foreach ($embeddedClasses as $propertyName => $mapping) {
            $reflProperty = $reflClass->getProperty($propertyName);
            $reflProperty->setAccessible(true);

            if (!$embeddable = $reflProperty->getValue($entity)) {
                continue;
            }

            $embeddableValue = $this->getEmbeddableValue($mapping['embeddable'], $embeddable);
            $reflProperty->setValue($entity, $embeddableValue);
        }
    }

    /**
     * @param array  $mappings
     * @param object $embeddable
     *
     * @return array
     */
    private function getEmbeddableValue(array $mappings, $embeddable)
    {
        if (!is_object($embeddable)) {
            throw new \UnexpectedValueException();
        }

        $embeddableClass = get_class($embeddable);
        $embeddableValue = [];
        $reflClass = new ReflectionClass($embeddableClass);

        foreach ($mappings as $mapping) {
            $reflProperty = $reflClass->getProperty($mapping['propertyName']);
            $reflProperty->setAccessible(true);

            $indexName = $mapping['name'] ?: $mapping['propertyName'];
            $value = $reflProperty->getValue($embeddable);

            $embeddableValue[$indexName] = Type::getType($mapping['type'])->convertToDatabaseValue($value);
        }

        return $embeddableValue;
    }
}
