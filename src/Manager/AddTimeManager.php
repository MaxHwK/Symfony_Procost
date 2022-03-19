<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use App\Entity\WorkingDays;
use App\Event\WorkingDaysCreated;

class AddTimeManager
{
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(WorkingDays $workingDays): void
    {
        $this->em->persist($workingDays);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new WorkingDaysCreated($workingDays));
    }
}
