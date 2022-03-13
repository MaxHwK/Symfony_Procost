<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Job;

final class JobCreated
{
    private Job $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function getJob(): Job
    {
        return $this->job;
    }
}
