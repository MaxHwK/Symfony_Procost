<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use App\Entity\Job;
use App\Event\JobCreated;

class JobManager
{
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(Job $job): void
    {
        $this->em->persist($job);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new JobCreated($job));
    }

    public function update()
    {
        $this->em->flush();
    }

    public function delete(Job $job)
    {
        $this->em->remove($job);
        $this->em->flush();
    }
}
