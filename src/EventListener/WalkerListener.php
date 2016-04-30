<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\Common\EventSubscriber;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataFactory;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadataInterface;
use Sergiors\Taxonomy\Configuration\Metadata\IndexMetadataInterface;
use Sergiors\Taxonomy\Type\Type;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
abstract class WalkerListener implements EventSubscriber
{
    /**
     * @var ClassMetadataFactory
     */
    private $metadataFactory;

    /**
     * @param ClassMetadataFactory $metadataFactory
     */
    public function __construct(ClassMetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @param array $scheduledEntity
     */
    protected function applyValueObject(array $scheduledEntity)
    {
        foreach ($scheduledEntity as $entity) {
            $className = get_class($entity);
            $classMetadata = $this->metadataFactory->getMetadataForClass($className);
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

        foreach ($embeddedClasses as $embeddedMetadata) {
            if (!$embeddable = $embeddedMetadata->getValue($entity)) {
                continue;
            }

            $embeddableValue = $this->getEmbeddableValue($embeddedMetadata->getEmbeddableClasses(), $embeddable);
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
                $indexName = $embeddableMetadata->getNameAttribute();
                $indexType = $embeddableMetadata->getTypeAttribute();
                $indexValue = $embeddableMetadata->getValue($object);

                $embeddableValue[$indexName] = Type::getType($indexType)->convertToDatabaseValue($indexValue);
            }

            if ($embeddableMetadata instanceof EmbeddedMetadataInterface) {
                $embeddableValue[$embeddableMetadata->getPropertyName()] = $this->getEmbeddableValue(
                    $embeddableMetadata->getEmbeddableClasses(),
                    $embeddableMetadata->getValue($object)
                );
            }
        }

        return $embeddableValue;
    }
}
