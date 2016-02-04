<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Instantiator\Instantiator;
use Metadata\MetadataFactory;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class PostLoadListener implements EventSubscriber
{
    /**
     * @var MetadataFactory
     */
    private $metadataFactory;

    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * @param MetadataFactory $metadataFactory
     */
    public function __construct(MetadataFactory $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
        $this->instantiator = new Instantiator();
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
        ];
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        $entityClass = get_class($entity);
        $reflClass = new \ReflectionClass($entityClass);
        $classMetadata = $this->metadataFactory->getMetadataForClass($entityClass);

        foreach ($classMetadata->getEmbeddedClasses() as $propertyName => $mapping) {
            $reflProperty = $reflClass->getProperty($propertyName);
            $reflProperty->setAccessible(true);

            $embeddableValue = $reflProperty->getValue($entity);
            $embeddableObject = $this->getEmbeddableObject($mapping, $embeddableValue);

            $reflProperty->setValue($entity, $embeddableObject);
        }
    }

    /**
     * @param array $mapping
     * @param array $embeddableValue
     *
     * @return object
     */
    private function getEmbeddableObject(array $mapping, array $embeddableValue)
    {
        if (!class_exists($mapping['class'])) {
            throw new \RuntimeException();
        }

        $embeddable = $this->instantiator->instantiate($mapping['class']);
        $reflClass = new \ReflectionClass($mapping['class']);

        foreach ($mapping['embeddable'] as $embeddableMapping) {
            $reflProperty = $reflClass->getProperty($embeddableMapping['propertyName']);
            $reflProperty->setAccessible(true);

            $indexName = $embeddableMapping['name'] ?: $embeddableMapping['propertyName'];
            if (!isset($embeddableValue[$indexName])) {
                continue;
            }

            $reflProperty->setValue($embeddable, $embeddableValue[$indexName]);
        }

        return $embeddable;
    }
}
