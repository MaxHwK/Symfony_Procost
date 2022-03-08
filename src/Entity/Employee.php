<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 * @ORM\Table(name="employee")
 */
class Employee
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ doit être rempli !")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ doit être rempli !")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champ doit être rempli !")
     * @Assert\Email (message="Email non valide !")
     */
    private $email;

    /**
     * @ORM\JoinColumn(nullable=false,name="job_id")
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="employees")
     * @Assert\NotBlank(message="Ce champ doit être rempli !")
     */
    private $job;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="Ce champ doit être rempli !")
     */
    private $dailyCost;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $hiringDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkingHours", mappedBy="employee")
     */
    private $timesList;

    public function __construct()
    {
        $this->hiringDate = new \DateTime();
        $this->timesList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getJob()
    {
        return $this->job;
    }

    public function setJob($job): Employee
    {
        $this->job = $job;

        return $this;
    }

    public function getDailyCost()
    {
        return $this->dailyCost;
    }

    public function setDailyCost($dailyCost): Employee
    {
        $this->dailyCost = $dailyCost;

        return $this;
    }

    public function getHiringDate(): ?\DateTimeInterface
    {
        return $this->hiringDate;
    }

    public function setHiringDate(\DateTimeInterface $hiringDate): self
    {
        $this->hiringDate = $hiringDate;

        return $this;
    }

    public function getTimesList(): Collection
    {
        return $this->timesList;
    }

    public function addOneInTimesList(WorkingHours $value): self
    {
        if (!$this->timesList->contains($value)) {
            $this->timesList[] = $value;
            $value->setEmployee($this);
        }

        return $this;
    }

    public function removeOneInTimesList(WorkingHours $value): self
    {
        if ($this->timesList->contains($value)) {
            $this->timesList->removeElement($value);
            if ($value->getEmployee() === $this) {
                $value->setEmployee(null);
            }
        }

        return $this;
    }
    
}
