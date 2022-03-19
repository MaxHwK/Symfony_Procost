<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\WorkingDays;

final class WorkingDaysCreated
{
    private WorkingDays $workingDays;

    public function __construct(WorkingDays $workingDays)
    {
        $this->workingDays = $workingDays;
    }

    public function getWorkingDays(): WorkingDays
    {
        return $this->workingDays;
    }
}
