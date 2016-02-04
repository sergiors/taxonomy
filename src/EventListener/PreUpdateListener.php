<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class PreUpdateListener extends WalkerListener
{
    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate
        ];
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $uow = $event->getEntityManager()->getUnitOfWork();
        $this->applyValueObject($uow->getScheduledEntityUpdates());
    }
}
