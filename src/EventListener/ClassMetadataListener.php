<?php
namespace Sergiors\Taxonomy\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Metadata\MetadataFactory;
use Doctrine\ORM\Events;
use Doctrine\DBAL\Types\Type;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ClassMetadataListener implements EventSubscriber
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
            Events::loadClassMetadata
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $event
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $className = $event->getClassMetadata()->getName();
        $classMetadata = $this->metadataFactory->getMetadataForClass($className);
        $taxonomyField = $classMetadata->getTaxonomy();

        if ($event->getClassMetadata()->hasField($taxonomyField) || !$classMetadata->getTaxonomy()) {
            return;
        }

        $event->getClassMetadata()->mapField([
            'fieldName' => $taxonomyField,
            'columnName' => $classMetadata->getColumn()->name,
            'type' => Type::JSON_ARRAY
        ]);
    }
}