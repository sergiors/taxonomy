<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\Column;
use Sergiors\Taxonomy\Configuration\Metadata\ClassMetadataFactory;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class ClassMetadataListener implements EventSubscriber
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

        /** @var \Sergiors\Taxonomy\Configuration\Metadata\EmbeddedMetadataInterface $embeddedMetadata */
        foreach ($classMetadata->getEmbeddedClasses() as $embeddedMetadata) {
            if (null === $embeddedMetadata->getColumnAttribute()
                || false === $embeddedMetadata->getColumnAttribute() instanceof Column
            ) {
                throw new \RuntimeException(
                    sprintf(
                        'You must set property column in "%s::%s()"',
                        $embeddedMetadata->getClassName(),
                        $embeddedMetadata->getPropertyName()
                    )
                );
            }

            $event->getClassMetadata()->mapField([
                'fieldName'  => $embeddedMetadata->getPropertyName(),
                'columnName' => $embeddedMetadata->getColumnAttribute()->name,
                'type'       => $embeddedMetadata->getColumnAttribute()->type,
            ]);
        }
    }
}
