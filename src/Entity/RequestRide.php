<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\RequestRideRepository::class)]

class RequestRide
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "number_seats", type: "integer", nullable: true)]
    private $numberSeats;

    #[ORM\Column(name: "departureTime", type: "datetime", nullable: true)]
    private $departuretime;

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

    public function getNumberSeats(): ?int
    {
        return $this->numberSeats;
    }

    public function setNumberSeats(?int $numberSeats): static
    {
        $this->numberSeats = $numberSeats;

        return $this;
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
