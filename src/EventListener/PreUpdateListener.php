<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PreUpdateListener extends ObjectWalkerListener
{
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate
        ];
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $uow = $event->getEntityManager()->getUnitOfWork();
        $this->applyValueObject($uow->getScheduledEntityUpdates());
    }
}
