<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\DBAL\Types\Type;
use Metadata\MetadataFactory;

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
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $event
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $className = $event->getClassMetadata()->getName();
        $classMetadata = $this->metadataFactory->getMetadataForClass($className);

        foreach ($classMetadata->getEmbeddedList() as $embeddedMetadata) {
            if (!$columnAnnotation = $embeddedMetadata->getColumn()) {
                continue;
            }

            $event->getClassMetadata()->mapField([
                'fieldName' => $embeddedMetadata->name,
                'columnName' => $columnAnnotation->name,
                'type' => Type::JSON_ARRAY,
            ]);
        }
    }
}
