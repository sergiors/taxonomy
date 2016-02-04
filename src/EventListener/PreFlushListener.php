<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreFlushEventArgs;

class PreFlushListener extends ObjectWalkerListener
{
    public function getSubscribedEvents()
    {
        return [
            Events::preFlush
        ];
    }

    public function preFlush(PreFlushEventArgs $event)
    {
        $uow = $event->getEntityManager()->getUnitOfWork();
        $this->applyValueObject($uow->getScheduledEntityInsertions());
    }
}
