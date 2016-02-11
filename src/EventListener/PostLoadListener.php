<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Instantiator\Instantiator;
use Metadata\MetadataFactory;
use Sergiors\Taxonomy\Type\Type;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadataInterface;
use Sergiors\Taxonomy\Configuration\Metadata\IndexMetadataInterface;

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
     * @param EmbeddedMetadataInterface $embeddedMetadata
     * @param array                     $data
     *
     * @return object
     */
    private function getEmbeddedObject(EmbeddedMetadataInterface $embeddedMetadata, array $data)
    {
        $embedded = $this->instantiator->instantiate($embeddedMetadata->getClass());

        foreach ($embeddedMetadata->getEmbeddableList() as $embeddableMetadata) {
            if ($embeddableMetadata instanceof IndexMetadataInterface) {
                $type = $embeddableMetadata->getIndex()->type;
                $name = $embeddableMetadata->getIndex()->name ?: $embeddableMetadata->name;
                $value = Type::getType($type)->convertToPHPValue($this->getOr($data, $name));

                $embeddableMetadata->setValue($embedded, $value);
            }

            if ($embeddableMetadata instanceof EmbeddedMetadataInterface) {
                $object = $this->getEmbeddedObject(
                    $embeddableMetadata,
                    $this->getOr($data, $embeddableMetadata->getPropertyName(), [])
                );
                $embeddableMetadata->setValue($embedded, $object);
            }
        }

        return $embedded;
    }

    private function getOr($map, $key, $default = null)
    {
        if (is_null($key)) {
            return $map;
        }

        if (isset($map[$key])) {
            return $map[$key];
        }

        return $default;
    }
}
