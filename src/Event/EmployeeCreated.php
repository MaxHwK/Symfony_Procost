<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Employee;

final class EmployeeCreated
{
    private Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }
}
