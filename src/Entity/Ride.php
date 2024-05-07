<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "departureTime", type: "datetime", nullable: true)]
    #[Assert\NotNull(message: "Departure time's field cannot be empty")]
    #[Assert\GreaterThan("now", message: "Departure time must be valid")]
    private $departuretime;

    #[ORM\Column(name: "availableSeats", type: "integer", nullable: true)]
    #[Assert\NotNull(message: "Available seats' field cannot be empty")]
    #[Assert\PositiveOrZero(message: "Available seats must be a positive number or zero")]
    private $availableseats;

    #[ORM\Column(name: "driver", type: "string", length: 255, nullable: true)]
    #[Assert\NotNull(message: "Driver's name's field cannot be empty")]
    private $driver;

    #[ORM\Column(name: "startLocation", type: "string", length: 255, nullable: true)]
    #[Assert\NotNull(message: "Start location's field cannot be empty")]
    private $startlocation;

    #[ORM\Column(name: "endLocation", type: "string", length: 255, nullable: true)]
    #[Assert\NotNull(message: "End location's field cannot be empty")]
    #[Assert\NotEqualTo(propertyPath: "startlocation", message: "End location must be different from start location")]
    private $endlocation;

    #[ORM\Column(name: "rating", type: "float", nullable: false)]
    //#[Assert\NotNull(message: "Rating cannot be empty")]
    //#[Assert\GreaterThanOrEqual(0, message: "Rating must be a positive number or zero")]
    private $rating;

    #[ORM\Column(name: "reports", type: "json", nullable: true)]
     private $reports;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparturetime(): ?\DateTimeInterface
    {
        return $this->departuretime;
    }

    public function setDeparturetime(?\DateTimeInterface $departuretime): self
    {
        $this->departuretime = $departuretime;

        return $this;
    }

    public function getAvailableseats(): ?int
    {
        return $this->availableseats;
    }

    public function setAvailableseats(?int $availableseats): self
    {
        $this->availableseats = $availableseats;

        return $this;
    }

    public function getDriver(): ?string
    {
        return $this->driver;
    }

    public function setDriver(?string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getStartlocation(): ?string
    {
        return $this->startlocation;
    }

    public function setStartlocation(?string $startlocation): self
    {
        $this->startlocation = $startlocation;

        return $this;
    }

    public function getEndlocation(): ?string
    {
        return $this->endlocation;
    }

    public function setEndlocation(?string $endlocation): self
    {
        $this->endlocation = $endlocation;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReports(): ?array
    {
        return $this->reports;
    }

    public function setReports(?array $reports): self
    {
        $this->reports = $reports;

        return $this;
    }
}
