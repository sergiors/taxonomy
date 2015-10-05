<?php
namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Metadata\MetadataFactory;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataInterface;
use ReflectionClass;

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
            Events::preFlush
        ];
    }

    /**
     * @param PreFlushEventArgs $event
     */
    public function preFlush(PreFlushEventArgs $event)
    {
        $uow = $event->getEntityManager()->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($entity));
            $reflClass = new ReflectionClass(get_class($entity));

            if (!$reflClass->hasProperty($classMetadata->getTaxonomy())) {
                continue;
            }

            $this->populateTaxonomy($classMetadata, $reflClass, $entity);
        }
    }

    /**
     * @param ClassMetadataInterface $classMetadata
     * @param object $entity
     */
    private function populateTaxonomy(
        ClassMetadataInterface $classMetadata,
        ReflectionClass $reflClass,
        $entity
    ) {
        $reflProperty = $reflClass->getProperty($classMetadata->getTaxonomy());
        $reflProperty->setAccessible(true);

        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            $taxon = $propertyMetadata->getValue($entity);
            $taxonClass = $propertyMetadata->getTaxonClass();

            if (!$taxon instanceof $taxonClass) {
                continue;
            }

            $taxonomy = $reflProperty->getValue($entity) ?: [];
            $taxonomy[$propertyMetadata->name] = $this->getTaxonData($taxon);

            $reflProperty->setValue($entity, $taxonomy);
        }
    }

    /**
     * @param object $taxon
     * @return array
     */
    private function getTaxonData($taxon)
    {
        $reflClass = new ReflectionClass(get_class($taxon));
        $data = [];

        foreach ($reflClass->getProperties() as $property) {
            $property->setAccessible(true);
            $data[$property->name] = $property->getValue($taxon);
        }

        return $data;
    }
}