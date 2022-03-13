<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\WorkingHours;

final class WorkingHoursCreated
{
    private WorkingHours $workingHours;

    public function __construct(WorkingHours $workingHours)
    {
        $this->workingHours = $workingHours;
    }

    public function getWorkingHours(): WorkingHours
    {
        return $this->workingHours;
    }
}
