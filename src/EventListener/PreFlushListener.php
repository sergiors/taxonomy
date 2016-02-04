<?php

namespace Sergiors\Taxonomy\EventListener;

use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreFlushEventArgs;

/**
 * @author Sérgio Rafael Siqueira <sergio@inbep.com.br>
 */
class PreFlushListener extends ObjectWalkerListener
{
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
        $this->applyValueObject($uow->getScheduledEntityInsertions());
    }
}
