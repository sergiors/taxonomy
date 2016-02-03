<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
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

        foreach ($classMetadata->getEmbeddedClasses() as $propertyName => $mapping) {
            if (null === $mapping['column']) {
                continue;
            }

            $event->getClassMetadata()->mapField([
                'fieldName' => $propertyName,
                'columnName' => $mapping['column']->name,
                'type' => Type::JSON_ARRAY
            ]);
        }
    }
}
