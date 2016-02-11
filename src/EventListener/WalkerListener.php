<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\Common\EventSubscriber;
use Metadata\MetadataFactory;
use Sergiors\Taxonomy\Type\Type;
use Sergiors\Taxonomy\Configuration\Metadata\IndexMetadataInterface;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadataInterface;

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
            if (!$embeddable = $embeddedMetadata->getValue($entity)) {
                continue;
            }

            $embeddableValue = $this->getEmbeddableValue($embeddedMetadata->getEmbeddableList(), $embeddable);
            $embeddedMetadata->setValue($entity, $embeddableValue);
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
            if (!$embeddableMetadata->getValue($object)) {
                continue;
            }

            if ($embeddableMetadata instanceof IndexMetadataInterface) {
                $indexName = $embeddableMetadata->getIndex()->name ?: $embeddableMetadata->name;
                $indexType = $embeddableMetadata->getIndex()->type;
                $indexValue = $embeddableMetadata->getValue($object);

                $embeddableValue[$indexName] = Type::getType($indexType)->convertToDatabaseValue($indexValue);
            }

            if ($embeddableMetadata instanceof EmbeddedMetadataInterface) {
                $embeddableValue[$embeddableMetadata->name] = $this->getEmbeddableValue(
                    $embeddableMetadata->getEmbeddableList(),
                    $embeddableMetadata->getValue($object)
                );
            }
        }

        return $embeddableValue;
    }
}
