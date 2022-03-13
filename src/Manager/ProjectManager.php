<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use App\Entity\Job;
use App\Entity\Project;
use App\Event\ProjectCreated;

class ProjectManager
{
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(Project $project): void
    {
        $this->em->persist($project);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new ProjectCreated($project));
    }

    public function update()
    {
        $this->em->flush();
    }

    public function delete(Project $project)
    {
        $this->em->remove($project);
        $this->em->flush();
    }
}
