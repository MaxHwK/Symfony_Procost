<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @ORM\Table(name="project")
 */
class Project
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
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champ doit être rempli !")
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="Ce champ doit être rempli !")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Type(type="\DateTimeInterface")
     */
    private $deliveryDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkingHours", mappedBy="project")
     */
    private $timesList;

    public function __construct()
    {
        $this->creationDate = new \DateTime();
        $this->deliveryDate = null;
        $this->timesList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(\DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

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
