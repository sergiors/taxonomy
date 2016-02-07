<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Instantiator\Instantiator;
use Metadata\MetadataFactory;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadata;
use Sergiors\Taxonomy\Type\Type;

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
        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($entity));

        foreach ($classMetadata->getEmbeddedList() as $propertyName => $embeddedMetadata) {
            $embeddedValue = $embeddedMetadata->getValue($entity);
            $embeddedObject = $this->getEmbeddedObject($embeddedMetadata, $embeddedValue);
            $embeddedMetadata->setValue($entity, $embeddedObject);
        }
    }

    /**
     * @param EmbeddedMetadata $embeddedMetadata
     * @param array            $embeddedValue
     *
     * @return object
     */
    private function getEmbeddedObject(EmbeddedMetadata $embeddedMetadata, array $embeddedValue)
    {
        $embedded = $this->instantiator->instantiate($embeddedMetadata->getClass());

        foreach ($embeddedMetadata->getEmbeddableList() as $embeddableMetadata) {
            $indexType = $embeddableMetadata->getIndex()->type;
            $indexName = $embeddableMetadata->getIndex()->name ?: $embeddableMetadata->name;

            if (isset($embeddedValue[$indexName])) {
                $value = Type::getType($indexType)->convertToPHPValue($embeddedValue[$indexName]);
                $embeddableMetadata->setValue($embedded, $value);
            }
        }

        return $embedded;
    }
}
