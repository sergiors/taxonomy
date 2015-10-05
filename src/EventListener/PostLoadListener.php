<?php
namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Instantiator\Instantiator;
use Metadata\MetadataFactory;
use ReflectionClass;

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
            Events::postLoad
        ];
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($entity));

        $reflClass    = new ReflectionClass(get_class($entity));
        $reflProperty = $reflClass->getProperty($classMetadata->getTaxonomy());
        $reflProperty->setAccessible(true);

        $taxonomy = $reflProperty->getValue($entity);

        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (!isset($taxonomy[$propertyMetadata->name])
                || !$reflClass->hasProperty($propertyMetadata->name)
            ) {
                continue;
            }


            $taxon = $this->instantiator->instantiate($propertyMetadata->getTaxonClass());
            $this->populateTaxon($taxon, $taxonomy[$propertyMetadata->name]);

            $reflTaxonProperty = $reflClass->getProperty($propertyMetadata->name);
            $reflTaxonProperty->setAccessible(true);
            $reflTaxonProperty->setValue($entity, $taxon);
        }
    }

    private function populateTaxon($taxon, $data) {
        $reflClass = new ReflectionClass(get_class($taxon));

        foreach ($reflClass->getProperties() as $property) {
            if (!isset($data[$property->name])) {
                continue;
            }

            $reflProperty = $reflClass->getProperty($property->name);
            $reflProperty->setAccessible(true);
            $reflProperty->setValue($taxon, $data[$property->name]);
        }
    }
}