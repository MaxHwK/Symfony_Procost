<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkingHoursRepository")
 * @ORM\Table(name="working_hours")
 */
class WorkingHours
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="timesList")
     * @ORM\JoinColumn(nullable=false, name="project_id")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee" , inversedBy="timesList")
     * @ORM\JoinColumn(nullable=false, name="employee_id")
     */
    private $employee;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbHours;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $creationDate;

    public function __construct()
    {
        $this->creationDate = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setEmployee($employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getNbHours(): ?int
    {
        return $this->nbHours;
    }

    public function setNbHours(int $nbHours): self
    {
        $this->nbHours = $nbHours;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }
    
}
