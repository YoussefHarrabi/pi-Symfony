<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\InjuryRepository::class)]
#[ORM\Table(name: "injury")]
class Injury
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: "incidentId", type: "integer", nullable: true)]
    private ?int $incidentId = null;

    #[ORM\Column(name: "type", type: "string", length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(name: "severity", type: "string", length: 255, nullable: true)]
    private ?string $severity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIncidentId(): ?int
    {
        return $this->incidentId;
    }

    public function setIncidentId(?int $incidentId): static
    {
        $this->incidentId = $incidentId;

        return $this;
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

    public function getSeverity(): ?string
    {
        return $this->severity;
    }

    public function setSeverity(?string $severity): static
    {
        $this->severity = $severity;

        return $this;
    }
}
