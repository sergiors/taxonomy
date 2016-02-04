<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class PrePersistListener extends WalkerListener
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist
        ];
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $uow = $event->getEntityManager()->getUnitOfWork();
        $this->applyValueObject($uow->getScheduledEntityInsertions());
    }
}
