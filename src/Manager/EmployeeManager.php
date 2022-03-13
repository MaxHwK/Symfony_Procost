<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

use App\Entity\Employee;
use App\Event\EmployeeCreated;

class EmployeeManager
{
    private EntityManagerInterface $em;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(Employee $employee): void
    {
        $this->em->persist($employee);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new EmployeeCreated($employee));
    }

    public function update()
    {
        $this->em->flush();
    }

    public function delete(Employee $employee)
    {
        $this->em->remove($employee);
        $this->em->flush();
    }
}
