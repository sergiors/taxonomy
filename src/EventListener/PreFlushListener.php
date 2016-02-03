<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Metadata\MetadataFactory;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class PreFlushListener implements EventSubscriber
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
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preFlush,
        ];
    }

    /**
     * @param PreFlushEventArgs $event
     */
    public function preFlush(PreFlushEventArgs $event)
    {
        $uow = $event->getEntityManager()->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
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
        $reflClass = new \ReflectionClass($entityClass);

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
        $reflClass = new \ReflectionClass($embeddableClass);

        foreach ($mappings as $mapping) {
            $reflProperty = $reflClass->getProperty($mapping['propertyName']);
            $reflProperty->setAccessible(true);

            $indexName = $mapping['name'] ?: $mapping['propertyName'];
            $embeddableValue[$indexName] = $reflProperty->getValue($embeddable);
        }

        return $embeddableValue;
    }
}
