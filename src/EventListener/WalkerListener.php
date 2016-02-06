<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\Common\EventSubscriber;
use Metadata\MetadataFactory;
use Sergiors\Taxonomy\Type\Type;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
abstract class WalkerListener implements EventSubscriber
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
            $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($entity));
            $this->applyEmbeddedValueToEntity($classMetadata->getEmbeddedList(), $entity);
        }
    }

    /**
     * @param array  $embeddedList
     * @param object $entity
     */
    private function applyEmbeddedValueToEntity(array $embeddedList, $entity)
    {
        if (!is_object($entity)) {
            throw new \UnexpectedValueException();
        }

        foreach ($embeddedList as $propertyName => $embeddedMetadata) {
            if ($embeddable = $embeddedMetadata->getValue($entity)) {
                $embeddableValue = $this->getEmbeddableValue($embeddedMetadata->getEmbeddableList(), $embeddable);
                $embeddedMetadata->setValue($entity, $embeddableValue);
            }
        }
    }

    /**
     * @param array  $embeddableList
     * @param object $object
     *
     * @return array
     */
    private function getEmbeddableValue(array $embeddableList, $object)
    {
        if (!is_object($object)) {
            throw new \UnexpectedValueException();
        }

        $embeddableValue = [];

        foreach ($embeddableList as $embeddableMetadata) {
            $indexName = $embeddableMetadata->getIndex()->name ?: $embeddableMetadata->name;
            $indexType = $embeddableMetadata->getIndex()->type;
            $value = $embeddableMetadata->getValue($object);

            $embeddableValue[$indexName] = Type::getType($indexType)->convertToDatabaseValue($value);
        }

        return $embeddableValue;
    }
}
