<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Project;

final class ProjectCreated
{
    private Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
