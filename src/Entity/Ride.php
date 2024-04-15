<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\RideRepository::class)]

class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "departureTime", type: "datetime", nullable: true)]
    private $departuretime;

    #[ORM\Column(name: "availableSeats", type: "integer", nullable: true)]
    private $availableseats;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name: "driver", referencedColumnName: "ID")]
    private $driver;

    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: "startLocation", referencedColumnName: "id")]
    private $startlocation;

    #[ORM\ManyToOne(targetEntity: Location::class)]
    #[ORM\JoinColumn(name: "endLocation", referencedColumnName: "id")]
    private $endlocation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparturetime(): ?\DateTimeInterface
    {
        return $this->departuretime;
    }

    public function setDeparturetime(?\DateTimeInterface $departuretime): static
    {
        $this->departuretime = $departuretime;

        return $this;
    }

    public function getAvailableseats(): ?int
    {
        return $this->availableseats;
    }

    public function setAvailableseats(?int $availableseats): static
    {
        $this->availableseats = $availableseats;

        return $this;
    }

    public function getDriver(): ?Utilisateurs
    {
        return $this->driver;
    }

    public function setDriver(?Utilisateurs $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getStartlocation(): ?Location
    {
        return $this->startlocation;
    }

    public function setStartlocation(?Location $startlocation): static
    {
        $this->startlocation = $startlocation;

        return $this;
    }

    public function getEndlocation(): ?Location
    {
        return $this->endlocation;
    }

    public function setEndlocation(?Location $endlocation): static
    {
        $this->endlocation = $endlocation;

        return $this;
    }
}
