<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use App\Entity\WorkingHours;
use App\Event\WorkingHoursCreated;

class AddTimeManager
{
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(WorkingHours $workingHours): void
    {
        $this->em->persist($workingHours);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new WorkingHoursCreated($workingHours));
    }
}
