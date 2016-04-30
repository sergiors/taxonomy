<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Instantiator\Instantiator;
use Sergiors\Taxonomy\Type\Type;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataFactory;
use Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadataInterface;
use Sergiors\Taxonomy\Configuration\Metadata\IndexMetadataInterface;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class PostLoadListener implements EventSubscriber
{
    /**
     * @var ClassMetadataFactory
     */
    private $metadataFactory;

    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * @param ClassMetadataFactory $metadataFactory
     */
    public function __construct(ClassMetadataFactory $metadataFactory)
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

        foreach ($classMetadata->getEmbeddedClasses() as $propertyName => $embeddedMetadata) {
            if (!is_array($embeddedValue = $embeddedMetadata->getValue($entity))) {
                continue;
            }

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
        $embedded = $this->instantiator->instantiate($embeddedMetadata->getClassAttribute());

        foreach ($embeddedMetadata->getEmbeddableClasses() as $embeddableMetadata) {
            if ($embeddableMetadata instanceof IndexMetadataInterface) {
                $name = $embeddableMetadata->getNameAttribute();
                $type = $embeddableMetadata->getTypeAttribute();
                $value = Type::getType($type)->convertToPHPValue($this->get($data, $name));

                $embeddableMetadata->setValue($embedded, $value);
            }

            if ($embeddableMetadata instanceof EmbeddedMetadataInterface) {
                $object = $this->getEmbeddedObject(
                    $embeddableMetadata,
                    $this->get($data, $embeddableMetadata->getPropertyName(), [])
                );
                $embeddableMetadata->setValue($embedded, $object);
            }
        }

        return $embedded;
    }

    private function get(array $map, $key, $default = null)
    {
        if (isset($map[$key])) {
            return $map[$key];
        }

        return $default;
    }
}
