<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Incident;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:"App\Repository\InjuryRepository"::class)]
class Injury
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private int $id;

   
    #[Assert\NotBlank(message: "Incident ID must not be empty")]
    #[ORM\ManyToOne(targetEntity: "App\Entity\Incident")]
    #[ORM\JoinColumn(name: "IncidentId", referencedColumnName: "IncidentId")]
    private $incident;

    #[ORM\Column(name: "type", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Type must not be empty")]
    private ?string $type;

    #[ORM\Column(name: "Number_pers", type: "integer", nullable: false)]
    #[Assert\Positive(message: "Number of people must be a positive integer")]
    private int $numberPers;

    #[ORM\Column(name: "severity", type: "string", length: 255, nullable: true)]
    private ?string $severity;

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNumberPers(): ?int
    {
        return $this->numberPers;
    }

    public function setNumberPers(int $numberPers): static
    {
        $this->numberPers = $numberPers;

        return $this;
    }

    public function getSeverity(): ?string
    {
        return $this->severity;
    }

    public function setSeverity(?string $severity): static
    {
        $this->severity = $severity;

        return $this;
    }

    public function getIncident(): ?Incident
    {
        return $this->incident;
    }

    public function setIncident(?Incident $incident): static
    {
        $this->incident = $incident;

        return $this;
    }


}
