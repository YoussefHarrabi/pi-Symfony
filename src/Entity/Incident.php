<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\IncidentRepository::class)]
#[ORM\Table(name: "incident")]
class Incident
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "IncidentId", type: "integer", nullable: false)]
    private ?int $incidentId = null;

    #[ORM\Column(name: "Type", type: "string", length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(name: "Place", type: "string", length: 255, nullable: true)]
    private ?string $place = null;

    #[ORM\Column(name: "Hour", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $hour = null;

    #[ORM\Column(name: "Description", type: "text", length: 65535, nullable: true)]
    private ?string $description = null;

    public function getIncidentId(): ?int
    {
        return $this->incidentId;
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

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(?\DateTimeInterface $hour): static
    {
        $this->hour = $hour;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
